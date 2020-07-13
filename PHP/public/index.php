<?php

require __DIR__.'/../vendor/autoload.php';

use App\Format\JSON;
use App\Format\XML;
use App\Format\YAML;
use App\Format\FromStringInterface;
use App\Format\NamedFormatInterface;

print_r ("Interfaces<br />");

$data = [
    "name" => "John",
    "surname" => "Doe"
];

$json = new JSON($data);
$xml = new XML($data);
$yml = new YAML($data);
//$base = new BaseFormat($data);


var_dump($json);
echo "<br />";
var_dump($xml);
echo "<br />";
var_dump($yml);
echo "<br />";
//var_dump($base);

print_r("<br /><br />Result of conversion<br /><br />");

$formats = [$json, $xml, $yml];
foreach($formats as $format){
  if($format instanceof NamedFormatInterface) {
    var_dump($format->getName());
    echo "<br />";
  }
  echo "<hr />";
  echo 'Class name: ';
  var_dump(get_class($format));
  echo "<br />";
  echo 'Convert result: ';
  var_dump($format->convert());
  echo "<br />";
  var_dump($format instanceof FromStringInterface);
  echo "<hr />";
  if($format instanceof FromStringInterface) {
    var_dump($format->convertFromString('{"name": "John", "surname": "Doe"}'));
    echo "<br />";
  }
}
