<?php
require_once __DIR__.'/../vendor/autoload.php';

$assembler = new HackAssembler\Assembler\Assembler();
$assembler->assembly($argv[1]);