<?php

namespace HackAssembler\Assembler;

use HackAssembler\Code\BinaryCodeTranslator;
use HackAssembler\Parser\Instruction\AInstruction;

class AssemblerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Assembler
     */
    private $assembler;

    public function setUp()
    {
        $this->assembler = new Assembler(new SymbolTable(), new BinaryCodeTranslator());
    }
    
    public function testItStartsStoringVariablesAtAddress16()
    {
        $address_16 = '0000000000010000';
        $address_17 = '0000000000010001';
        $commands = [
            new AInstruction('@variable1'),
            new AInstruction('@variable2'),
        ];
        $parser = $this->mockParserWithCommands($commands);
        $symbols = [
            ['name' => 'variable1', 'value' => 16],
            ['name' => 'variable2', 'value' => 17]
        ];
        $symbolTable = $this->mockSymbolTableWithSymbols($symbols);
        $this->assembler = new Assembler($symbolTable, new BinaryCodeTranslator());
        $output = $this->assembler->assembly($parser);
        $this->assertCount(2, $output);
        $this->assertEquals($address_16, $output[0]);
        $this->assertEquals($address_17, $output[1]);
    }

//    public function testItOnlyStoresAVariableOnce()
//    {
//        $address_16 = '0000000000010000';
//        $address_17 = '0000000000010001';
//        $output = $this->assembler->assembly(__DIR__.'/fixtures/program_with_two_variables_and_one_used_twice.asm');
//        $this->assertCount(3, $output);
//        $this->assertEquals($address_16, $output[0]);
//        $this->assertEquals($address_17, $output[1]);
//        $this->assertEquals($address_16, $output[2]);
//    }
//
//    public function testItDoesntIncludeLabelsInstructionsInTheOutput()
//    {
//        $output = $this->assembler->assembly(__DIR__.'/fixtures/program_with_one_label_and_one_instruction.asm');
//        $this->assertCount(1, $output);
//        $this->assertEquals('0000000000000000', $output[0]);
//    }


    private function mockParserWithCommands($commands)
    {
        $parser = $this->getMockBuilder('HackAssembler\Parser\Parser')
                            ->disableOriginalConstructor()
                            ->setMethods(['hasCommands', 'getCurrentCommand', 'next'])
                            ->getMock();

        $hasCommandsReturns = array_fill(0, count($commands), true);
        $hasCommandsReturns[] = false;
        $hasCommandsReturns = array_merge($hasCommandsReturns, $hasCommandsReturns);
        $parser->expects($this->exactly((count($commands)+1)*2))
            ->method('hasCommands')
            ->will(new \PHPUnit_Framework_MockObject_Stub_ConsecutiveCalls($hasCommandsReturns));

        $commands = array_merge($commands, $commands);
        $parser->expects($this->exactly(count($commands)*2))
            ->method('getCurrentCommand')
            ->will(new \PHPUnit_Framework_MockObject_Stub_ConsecutiveCalls($commands));

        return $parser;
    }

    private function mockSymbolTableWithSymbols($symbols)
    {
        $symbolTable = $this->getMockBuilder('HackAssembler\Assembler\SymbolTable')
                       ->setMethods(['add', 'contains', 'getAddress'])
                       ->getMock();


        $addStubParameters = [];
        $containsStubParameters = [];
        $getAddressValueMap = [];
        foreach($symbols as $symbol) {
            $addStubParameters[] = [$symbol['name'], $symbol['value']];
            $containsStubParameters[] = [$symbol['name']];
            $getAddressValueMap[] = [$symbol['name'], $symbol['value']];
        }

        $symbolTable
            ->expects($this->any())
            ->method('add')
            ->withConsecutive($addStubParameters);

        $symbolTable
            ->expects($this->any())
            ->method('contains')
            ->withConsecutive('variable1', 'variable2');

        $symbolTable
            ->expects($this->any())
            ->method('getAddress')
            ->will($this->returnValueMap($getAddressValueMap));

        return $symbolTable;
    }
}