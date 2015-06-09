<?php

namespace HackAssembler\Assembler;

class SymbolTableTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var SymbolTable;
     */
    private $symbolTable;

    public function setUp()
    {
        $this->symbolTable = new SymbolTable();
    }

    /**
     * @dataProvider addNewSymbolDataProvider
     */
    public function testCanAddANewSymbol($symbol, $address)
    {
        $this->assertFalse($this->symbolTable->contains($symbol));
        $this->symbolTable->add($symbol, $address);
        $this->assertEquals($address, $this->symbolTable->getAddress($symbol));
    }

    public function testAddingAnExistingSymbolWontChangeItsAddress()
    {
        $symbol = 'SYMBOL';
        $address = 99;
        $this->symbolTable->add($symbol, $address);
        $this->symbolTable->add($symbol, $address+1);
        $this->assertEquals($address, $this->symbolTable->getAddress($symbol));
    }

    /**
     * @dataProvider predefinedSymbolsDataProvider
     */
    public function testItHasPreDefinedSymbols($symbol, $address)
    {
        $this->assertEquals($address, $this->symbolTable->getAddress($symbol));
    }

    public function addNewSymbolDataProvider()
    {
        return [
            ['test', 16],
            ['SYMBOL', 31],
            ['LOOP', 1443],
            ['PRINT_SCREEN', 0]
        ];
    }

    public function predefinedSymbolsDataProvider()
    {
        return [
            ['SP', 0],
            ['LCL', 1],
            ['ARG', 2],
            ['THIS', 3],
            ['THAT', 4],
            ['R0', 0],
            ['R1', 1],
            ['R2', 2],
            ['R3', 3],
            ['R4', 4],
            ['R5', 5],
            ['R6', 6],
            ['R7', 7],
            ['R8', 8],
            ['R9', 9],
            ['R10', 10],
            ['R11', 11],
            ['R12', 12],
            ['R13', 13],
            ['R14', 14],
            ['R15', 15],
            ['SCREEN', 16384],
            ['KBD', 24576]
        ];
    }
}