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
        preg_match_all("/((\S* (?!-)\S*)|(\S*=\S*)|(\S\S))/", $toBeParsed, $matches);
        // $matches[2] = single dash
        // $matches[3] = double dash
        // $matches[4] = flags
        $this->parseFlags($matches);
        $this->parseSingle($matches);
        $this->parseDouble($matches);
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