<?php
declare(strict_types=1);
require __DIR__.'/../vendor/autoload.php';

use App\Format\JSON;
use App\Format\XML;
use App\Format\YAML;

print_r ("Anonymous functions<br /><br />");

$data = [
    "name" => "John",
    "surname" => "Doe"
];

$formats = [
  new JSON($data),
  new XML($data),
  new YAML($data)
];

$found = array_filter($formats, function($format){
  return $format->getName() === 'YAML';
});

var_dump($found);