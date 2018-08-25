<?php
namespace HackAssembler\Assembler;
use HackAssembler\Parser\Instruction\AInstruction;
use HackAssembler\Parser\Instruction\CInstruction;
use HackAssembler\Parser\Instruction\LabelInstruction;
use HackAssembler\Parser\Parser;
use HackAssembler\Code\BinaryCodeTranslator;

class Assembler
{
    private $symbolTable;
    private $variableAddress = 16;
    private $binaryCodeTranslator;

    public function __construct(SymbolTable $symbolTable, BinaryCodeTranslator $binaryCodeTranslator)
    {
        $this->symbolTable = $symbolTable;
        $this->binaryCodeTranslator = $binaryCodeTranslator;
    }

    public function assembly(Parser $parser)
    {
        $this->firstPass($parser);
        return $this->secondPass($parser);
    }

    private function firstPass(Parser $parser)
    {
//        $parser = new Parser($file);
        $currentAddress = 0;
        while($parser->hasCommands()) {
            $command = $parser->getCurrentCommand();

            if($command instanceof LabelInstruction) {
                $this->symbolTable->add($command->getLabel(), $currentAddress);
                $currentAddress--;
            }

            $currentAddress++;
            $parser->next();
        }
    }

    private function secondPass(Parser $parser)
    {
        $output = [];
        //$parser = new Parser($file);
        while($parser->hasCommands()) {
            $command = $parser->getCurrentCommand();

            if($this->isAVariable($command) && !$this->symbolTable->contains($command->getAddress())) {
                $this->symbolTable->add($command->getAddress(), $this->getNextVariableAddress());
            }

            if($command instanceof AInstruction || $command instanceof CInstruction) {
                $binary_code = $this->binaryCodeTranslator->translate($command, $this->symbolTable);
                $output[] = $binary_code;
            }

            $parser->next();
        }

        return $output;
    }

    private function getNextVariableAddress()
    {
        return $this->variableAddress++;
    }

    private function isAVariable($command)
    {
        return $command instanceof AInstruction && !is_numeric($command->getAddress());
    }
}