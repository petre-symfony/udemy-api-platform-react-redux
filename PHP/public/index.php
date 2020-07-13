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

function getFormatByName(array $formats, string $name): ?BaseFormat{
  foreach($formats as $format){
    if($format instanceof NamedFormatInterface && $format->getName() === $name){
      return $format;
    }
  }

  return null;
}

function justJumpData(BaseFormat $format):void {
  var_dump($format->convert());
}

$json = new JSON();

$formats = [
  new JSON($data),
  new XML($data),
  new YAML($data)
];

var_dump(getFormatByName($formats, 'XML'));
justJumpData($formats[0]);
