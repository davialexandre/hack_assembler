<?php
class CInstructionBuilder
{
    public static function buildInstructionString($dest, $comp, $jmp)
    {
        $instruction = '';
        if($dest) $instruction .= "$dest=";
        $instruction .= $comp;
        if($jmp) $instruction .= ";$jmp";
        return $instruction;
    }

}