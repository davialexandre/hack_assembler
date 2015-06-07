<?php
namespace HackAssembler\Parser\Instruction;

class AInstructionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider addressesDataProvider
     */
    public function testItCanParseAddressFromInstruction($instruction, $address)
    {
        $aInstruction = new AInstruction($instruction);
        $this->assertEquals($address, $aInstruction->getAddress());
    }

    public function addressesDataProvider()
    {
        return [
            ['@10', '10'],
            ['@SCREEN', 'SCREEN']
        ];
    }
}