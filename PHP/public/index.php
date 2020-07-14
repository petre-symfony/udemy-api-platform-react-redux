<?php
require __DIR__.'/../vendor/autoload.php';

use App\Kernel;

print_r("Annotations<br /><br />");

$kernel = new Kernel();
$kernel->boot();
$container = $kernel->getContainer();

var_dump($container->getServices());

echo "<hr />";
var_dump($container->getService('App\\Controller\\IndexController')->index());
var_dump($container->getService('App\\Controller\\PostController')->index());

