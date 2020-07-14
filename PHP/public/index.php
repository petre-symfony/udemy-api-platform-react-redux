<?php
require __DIR__.'/../vendor/autoload.php';

use App\Format\JSON;
use App\Format\XML;
use App\Format\YAML;
use App\Format\BaseFormat;
use App\Format\FromStringInterface;
use App\Format\NamedFormatInterface;

use App\Service\Serializer;
use App\Controller\IndexController;

print_r("Simple Service Container<br /><br />");

$data = [
  "name" => "John",
  "surname" => "Doe"
];

$serializer = new Serializer(new YAML());
$controller = new IndexController($serializer);

var_dump($controller->index());