<?php
namespace Models\Utilities\ParseArgv;

class ParseArgv
{

    public $hello = "Hello";
    private $argsParsed;
    public function __construct($input)
    {
        $this->argsParsed = array('FLAGS' => array(), 'SINGLE' => array(), 'DOUBLE' => array());
        $this->parse($input);
    }

    public function parse($input) {
        $toBeParsed = implode(" ", array_slice($input, 1));
        preg_match_all("/((\S{2,} (?!-)\S+)|(\S{2,}=\S+)|(\S[^\s-]))/", $toBeParsed, $matches);
        $not_matched = array_filter(explode(" ", preg_replace("/((\S{2,} (?!-)\S+)|(\S{3,}=\S+)|(\S[^\s-]))/", '', $toBeParsed)));
        if (sizeof($not_matched)) {
            echo "Invalid characters " . implode(" ", $not_matched) . "\n";
            exit(-1);
        }

        // $matches[2] = single dash
        // $matches[3] = double dash
        // $matches[4] = flags
        $this->parseFlags($matches);
        $this->parseSingle($matches);
        $this->parseDouble($matches);
    }

    public function print() {
        foreach($this->argsParsed as $key => $val) {
            echo $key . "\n";
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
    }

    private function parseFlags($matches) {
        $removed_null = array_values(array_filter($matches[4]));
        for ($i = 0; $i < sizeof($removed_null); $i++) {
            array_push($this->argsParsed["FLAGS"], substr($removed_null[$i], 1));
        }
    }

    private function parseSingle($matches) {
        $removed_null = array_values(array_filter($matches[2]));
        for ($i = 0; $i < sizeof($removed_null); $i++) {
            $temp = explode(" ", $removed_null[$i]);
            for ($j = 0; $j < sizeof($temp); $j+=2) {
                $val = array_pop($temp);
                $key = array_pop($temp);
                $this->argsParsed['SINGLE'][substr($key, 1)] = $val;
            }
        }
    }

    private function parseDouble($matches) {
        $removed_null = array_values(array_filter($matches[3]));
        for ($i = 0; $i < sizeof($removed_null); $i++) {
            $temp = explode("=", $removed_null[$i]);
            for ($j = 0; $j < sizeof($temp); $j++) {
                $val = array_pop($temp);
                $key = array_pop($temp);
                $this->argsParsed['DOUBLE'][substr($key, 2)] = $val;
            }
        }
    }

    public function getParsed()
    {
        return $this->argsParsed;
    }
}