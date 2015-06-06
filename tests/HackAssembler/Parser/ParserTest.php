<?php

namespace HackAssembler\Parser;

class ParserTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var Parser
     */
    private $parser;

    public function setUp()
    {
        $this->parser = new Parser();
    }

    /**
     * @dataProvider aInstructionsDataProvider
     */
    public function testItParsesAInstructions($instruction)
    {
        $this->assertInstanceOf('HackAssembler\Parser\Instruction\AInstruction', $this->parser->parseInstruction($instruction));
    }

    /**
     * @dataProvider labelsDataProvider
     */
    public function testItParsesLabels($label)
    {
        $this->assertInstanceOf('HackAssembler\Parser\Instruction\LabelInstruction', $this->parser->parseInstruction($label));
    }

    /**
     * @dataProvider cInstructionsDataProvider
     */
    public function testItParsesCInstructions($instruction)
    {
        $this->assertInstanceOf('HackAssembler\Parser\Instruction\CInstruction', $this->parser->parseInstruction($instruction));
    }

    /**
     * @dataProvider filesDataProvider
     */
    public function testItParsesAllCommandsInAFile($file, $numberOfCommands)
    {
        $parser = new Parser($file);
        $numberOfParsedCommands = 0;
        while($parser->hasCommands()) {
            $command = $parser->getCurrentCommand();
            $this->assertInstanceOf('HackAssembler\Parser\Instruction\Instruction', $command);
            $numberOfParsedCommands++;
            $parser->next();
        }
        $this->assertEquals($numberOfCommands, $numberOfParsedCommands);
    }

    /**
     * @dataProvider whiteSpaceDataProvider
     */
    public function testItIgnoresWhiteSpaceAndComments($instruction, $shouldBeNull)
    {
        $parserdInstruction = $this->parser->parseInstruction($instruction);
        if($shouldBeNull) {
            $this->assertNull($parserdInstruction);
        } else {
            $this->assertInstanceOf('HackAssembler\Parser\Instruction\Instruction', $parserdInstruction);
        }
    }

    public function aInstructionsDataProvider()
    {
        return [
            ['            @15'],
            ['@100'],
            ['@1 //this is an address']
        ];
    }

    public function cInstructionsDataProvider()
    {
        return [
            ['            D=1'],
            ['M=D'],
            ['A=D //this is an address']
        ];
    }

    public function labelsDataProvider()
    {
        return [
            ['            (LOOP)'],
            ['(PRINT)'],
            ['(BEGIN) //this is an address']
        ];
    }

    public function whiteSpaceDataProvider() {
        return [
            ['                   ', TRUE],
            [' ', TRUE],
            ['', TRUE],
            ['    D=M', FALSE],
            ['D=F         ', FALSE],
            ['      A=10    ', FALSE],
            ['//This is a comment', TRUE],
            ['//', TRUE],
            ['// D=M', TRUE],
            ['D=M //Assigns M to D', FALSE],
            ['  A=300 // Another commend', FALSE],
            ['@12 //An address', FALSE],
            ['//@12', TRUE],
            ['    @15 //Another address', FALSE]
        ];
    }

    public function filesDataProvider()
    {
        return [
            [__DIR__.'/fixtures/Empty.asm', 0],
            [__DIR__.'/fixtures/EmptyWithComments.asm', 0],
            [__DIR__.'/fixtures/Add.asm', 6],
            [__DIR__.'/fixtures/Max.asm', 19],
            [__DIR__.'/fixtures/Rect.asm', 27],
        ];
    }
}
