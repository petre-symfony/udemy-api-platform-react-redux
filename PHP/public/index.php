<?php

require __DIR__.'/../vendor/autoload.php';

use App\Format\JSON;
use App\Format\XML;
use App\Format\YAML;
use App\Format\BaseFormat;

print_r ("Typed arguments & return types<br /><br />");

$data = [
    "name" => "John",
    "surname" => "Doe"
];

function convertData(BaseFormat $format){
  return $format->convert();
}

var_dump(convertData(new \stdClass($data)));
