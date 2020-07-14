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
use App\Container;

print_r("Simple Service Container<br /><br />");

$data = [
  "name" => "John",
  "surname" => "Doe"
];

$serializer = new Serializer(new JSON());
$controller = new IndexController($serializer);

$container = new Container();
$container->addService('format.json', function() use ($container){
  return new JSON();
});
$container->addService('format.xml', function() use ($container){
  return new XML();
});
$container->addService('format', function() use ($container){
  $container->getService('format.json');
});
$container->addService('serializer', function() use ($container){
  return new Serializer($this->getService('format'));
});
$container->addService('controller.index', function() use ($container){
  return new IndexController($this->getService('serializer'));
});
var_dump($container);