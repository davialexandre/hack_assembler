<?php
namespace HackAssembler\Parser\Instruction;

class CInstruction extends Instruction
{
    protected $dest = null;
    protected $comp = null;
    protected $jmp = null;

    public function getDest()
    {
        return $this->dest;
    }

    public function getComp()
    {
        return $this->comp;
    }

    public function getJmp()
    {
        return $this->jmp;
    }

    protected function parse()
    {
        $parts = explode('=', $this->instruction);
        if(isset($parts[1])) {
            $this->dest = $parts[0];
            $remaining_parts = $parts[1];
        } else {
            $remaining_parts = $parts[0];
        }

        $parts = explode(';', $remaining_parts);
        $this->comp = $parts[0];
        if(isset($parts[1])) {
            $this->jmp = $parts[1];
        }
    }
}