<?php
namespace HackAssembler\Parser\Instruction;

class LabelInstruction extends Instruction
{
    protected $label;

    public function getLabel()
    {
        return $this->label;
    }
    
    protected function parse()
    {
        $matches = [];
        if(preg_match('/\((.+?)\)/', $this->instruction, $matches)) {
            $this->label = $matches[1];
        }
    }
}