<?php
declare(strict_types=1);
require __DIR__.'/../vendor/autoload.php';

use App\Format\JSON;
use App\Format\XML;
use App\Format\YAML;
use App\Format\BaseFormat;

print_r ("Reflections<br /><br />");

$class = new ReflectionClass(JSON::class);
var_dump($class);
$method = $class->getConstructor();
echo "<br />";
var_dump($method);
echo "<br />";
$parameters = $method->getParameters();
var_dump($parameters);
echo "<br />";

foreach($parameters as $parameter){
  $type = $parameter->getType();
  var_dump((string) $type);
  echo "<br />";
  var_dump($type->isBuiltin());
  echo "<br />";
  var_dump($parameter->allowsNull());
  echo "<br />";
  var_dump($parameter->getDefaultValue());
}