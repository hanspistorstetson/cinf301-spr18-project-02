<?php
require_once("./Models/Utilities/ParseArgv.php");
use Models\Utilities\ParseArgv\ParseArgv;

$parseArg = new ParseArgv($_SERVER['argv']);
$parsed = $parseArg->print();