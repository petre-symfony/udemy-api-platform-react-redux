<?php
require __DIR__.'/../vendor/autoload.php';

use App\Format\JSON;
use App\Format\XML;
use App\Format\YAML;
use App\Format\BaseFormat;
use App\Format\FromStringInterface;
use App\Format\NamedFormatInterface;

use App\Serializer;

print_r("Dependency Injection<br /><br />");

$data = [
  "name" => "John",
  "surname" => "Doe"
];

$serializer = new Serializer(new YAML());
var_dump($serializer->serialize($data));