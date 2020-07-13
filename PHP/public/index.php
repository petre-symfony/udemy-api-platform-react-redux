<?php
declare(strict_types=1);
require __DIR__.'/../vendor/autoload.php';

use App\Format\JSON;
use App\Format\XML;
use App\Format\YAML;
use App\Format\BaseFormat;
use App\Format\NamedFormatInterface;

print_r ("Typed arguments & return types<br /><br />");

$data = [
    "name" => "John",
    "surname" => "Doe"
];

function convertData(BaseFormat $format){
  return $format->convert();
}

function getFormatName(NamedFormatInterface $format):string {
  return $format->getName();
}

$json = new JSON();
var_dump(convertData($json));
echo "<br />";
var_dump(getFormatName($json));
