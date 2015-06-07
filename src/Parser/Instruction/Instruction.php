<?php
namespace HackAssembler\Parser\Instruction;

abstract class Instruction
{
    protected $instruction;

    public function __construct($instruction)
    {
        $this->instruction = $instruction;
        $this->parse();
    }

    protected abstract function parse();

    public function __toString()
    {
        return $this->instruction;
    }
}