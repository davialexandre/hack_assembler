<?php
namespace HackAssembler\Parser;

class Parser
{
    /**
     * @var null|\SplFileObject
     */
    private $file = null;

    /**
     * @var null|Instruction\Instruction
     */
    private $currentCommand = null;

    /**
     * @param null|Instruction\Instruction
     */
    private $nextCommand = null;

    public function __construct($file = null)
    {
        if($file) {
            $this->file = new \SplFileObject($file);
            $this->getNextTwoCommands();
        }
    }

    public function hasCommands()
    {
        return $this->currentCommand != null;
    }

    public function getCurrentCommand()
    {
        return $this->currentCommand;
    }

    public function next()
    {
        $this->getNextTwoCommands();
    }

    public function parseInstruction($instruction)
    {
        $instruction = $this->removeComments($instruction);
        $instruction = $this->removeWhiteSpace($instruction);

        if(empty($instruction)) return null;

        if($instruction[0] == '@') {
            return new Instruction\AInstruction($instruction);
        }

        if($instruction[0] == '(' && $instruction[strlen($instruction)-1] == ')') {
            return new Instruction\LabelInstruction($instruction);
        }

        if($instruction[0] != ' ' && $instruction[0] != '/') {
            return new Instruction\CInstruction($instruction);
        }
    }

    private function removeWhiteSpace($instruction)
    {
        return trim($instruction);
    }

    private function removeComments($instruction)
    {
        return preg_replace('/\/\/.*/', '', $instruction);
    }

    private function getNextTwoCommands()
    {
        $nextCommandInFile = $this->getNextCommandInFile();

        if($this->nextCommand) {
            $this->currentCommand = $this->nextCommand;
            $this->nextCommand = $nextCommandInFile;
        } else {
            $this->currentCommand = $nextCommandInFile;
            $this->nextCommand = $this->getNextCommandInFile();
        }
    }

    private function getNextCommandInFile()
    {
        $command = null;
        while(!$this->file->eof()) {
            $line = $this->file->fgets();
            $command = $this->parseInstruction($line);
            if($command) break;
        }
        return $command;
    }

}