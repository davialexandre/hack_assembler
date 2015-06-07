<?php
namespace HackAssembler\Parser\Instruction;

class CInstructionTest extends \PHPUnit_Framework_TestCase
{
    private $dests = [ null, 'M', 'D', 'MD', 'A', 'AM', 'AD', 'AMD' ];
    private $jmps = [ null, 'JGT', 'JEQ', 'JGE', 'JLT', 'JNE', 'JLE', 'JMP' ];
    private $comps = [
        '0',
        '1',
        '-1',
        'D',
        'A',
        'M',
        '!D',
        '!A',
        '!M',
        '-D',
        '-A',
        '-M',
        'D+1',
        'A+1',
        'M+1',
        'D-1',
        'A-1',
        'M-1',
        'D+A',
        'D+M',
        'D-A',
        'D-M',
        'A-D',
        'M-D',
        'D&A',
        'D&M',
        'D|A',
        'D|M'
    ];

    public function testItCanParseInstructionsPartsFromInstruction()
    {
        foreach ($this->dests as $dest) {
            foreach ($this->comps as $comp) {
                foreach ($this->jmps as $jmp) {
                    $instruction = new CInstruction($this->getInstructionString($dest, $comp, $jmp));
                    $this->assertEquals($dest, $instruction->getDest());
                    $this->assertEquals($comp, $instruction->getComp());
                    $this->assertEquals($jmp, $instruction->getJmp());
                }
            }
        }
    }

    private function getInstructionString($dest, $comp, $jmp)
    {
        $instruction = '';
        if($dest) $instruction .= "$dest=";
        $instruction .= $comp;
        if($jmp) $instruction .= ";$jmp";
        return $instruction;
    }
}