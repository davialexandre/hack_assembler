<?php
namespace HackAssembler\Parser\Instruction;

class AInstruction extends Instruction
{
    protected $address;

    public function getAddress()
    {
        return $this->address;
    }
    
    protected function parse()
    {
        $this->address = substr($this->instruction, 1);
    }
}