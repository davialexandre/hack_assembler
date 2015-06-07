<?php

namespace HackAssembler\Code;

use HackAssembler\Parser\Instruction\AInstruction;
use HackAssembler\Parser\Instruction\CInstruction;

class BinaryCodeTranslatorTest extends \PHPUnit_Framework_TestCase
{
    private $dests = [
        ''    => '000',
        'M'   => '001',
        'D'   => '010',
        'MD'  => '011',
        'A'   => '100',
        'AM'  => '101',
        'AD'  => '110',
        'AMD' => '111',
    ];

    private $jmps = [
        ''    => '000',
        'JGT' => '001',
        'JEQ' => '010',
        'JGE' => '011',
        'JLT' => '100',
        'JNE' => '101',
        'JLE' => '110',
        'JMP' => '111',
    ];

    private $comps = [
        '0'   => '0101010',
        '1'   => '0111111',
        '-1'  => '0111010',
        'D'   => '0001100',
        'A'   => '0110000',
        'M'   => '1110000',
        '!D'  => '0001101',
        '!A'  => '0110001',
        '!M'  => '1110001',
        '-D'  => '0001111',
        '-A'  => '0110011',
        '-M'  => '1111111',
        'D+1' => '0011111',
        'A+1' => '0110111',
        'M+1' => '1110111',
        'D-1' => '0001110',
        'A-1' => '0110010',
        'M-1' => '1110010',
        'D+A' => '0000010',
        'D+M' => '1000010',
        'D-A' => '0010011',
        'D-M' => '1010011',
        'A-D' => '0000111',
        'M-D' => '1000111',
        'D&A' => '0000000',
        'D&M' => '1000000',
        'D|A' => '0010101',
        'D|M' => '1010101',
    ];

    public function testItCanTranslateCInstructionsToBinaryCode()
    {
        foreach($this->comps as $comp_mnemonic => $comp_binary) {
            foreach($this->dests as $dest_mnemonic => $dest_binary) {
                foreach($this->jmps as $jmp_mnemonic => $jmp_binary) {
                    $expectedBinaryCode = "111{$comp_binary}{$dest_binary}{$jmp_binary}";
                    $instruction_string = \CInstructionBuilder::buildInstructionString($dest_mnemonic, $comp_mnemonic, $jmp_mnemonic);
                    $instruction = new CInstruction($instruction_string);
                    $this->assertEquals($expectedBinaryCode, BinaryCodeTranslator::translate($instruction));
                }
            }
        }
    }

    public function testItCanTranslateAInstructionsToBinaryCode()
    {
        for($i = 0; $i < 100; $i++) {
            $address = rand(0, 32767);
            $instruction = new AInstruction("@$address");
            $expected_binary_code = "0".str_pad(decbin($address), 15, '0', STR_PAD_LEFT);
            $this->assertEquals($expected_binary_code, BinaryCodeTranslator::translate($instruction));
        }
    }
}