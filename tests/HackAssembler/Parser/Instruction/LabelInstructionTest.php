<?php
namespace HackAssembler\Parser\Instruction;

class LabelInstructionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider labelsDataProvider
     */
    public function testItCanParseLabelFromInstruction($instruction, $label)
    {
        $instruction = new LabelInstruction($instruction);
        $this->assertEquals($label, $instruction->getLabel());
    }

    public function labelsDataProvider()
    {
        return [
            ['(LOOP)', 'LOOP'],
            ['(SCREEN)', 'SCREEN'],
            ['(begin)', 'begin']
        ];
    }
}