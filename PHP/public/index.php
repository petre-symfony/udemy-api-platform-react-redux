<?php

require __DIR__.'/../vendor/autoload.php';

use App\Format\JSON;
use App\Format\XML;
use App\Format\YAML;

print_r ("Inheritance<br />");

$data = [
    "name" => "John",
    "surname" => "Doe"
];

$json = new JSON($data);
$xml = new XML($data);
$yml = new YAML($data);


var_dump($json);
echo "<br />";
var_dump($xml);
echo "<br />";
var_dump($yml);
echo "<br />";

print_r("<br /><br />Result of conversion<br /><br />");
var_dump($json->convert());
echo "<br />";
var_dump($xml->convert());
echo "<br />";
var_dump($yml->convert());