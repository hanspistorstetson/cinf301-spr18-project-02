<?php
require_once("./Models/Utilities/ParseArgv.php");
use Models\Utilities\ParseArgv\ParseArgv;

$parseArg = new ParseArgv($_SERVER['argv']);
$parsed = $parseArg->getParsed();

foreach($parsed as $key => $val) {
    echo $key . "\n\n";

    foreach($val as $ikey => $ival) {
        if ($key == 'FLAGS') {
            echo $ival . "\n";
        } else {
            echo $ikey . " => ";
            $explode_comma = explode(",", $ival);
            if (sizeof($explode_comma) == 1) {
                echo $ival . " (". sizeof($ival) . " argument)\n";
            } else {
                for ($i = 0; $i < sizeof($explode_comma)-1; $i++) {
                    echo "[" . $i . "] " . $explode_comma[$i] . ", ";
                }
                echo "[" . (sizeof($explode_comma)-1) . "] " . $explode_comma[sizeof($explode_comma)-1] . " (" . sizeof($explode_comma) . " arguments)\n";

            }
        }
    }
    echo "\n";
}