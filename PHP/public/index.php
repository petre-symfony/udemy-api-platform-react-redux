<?php
require __DIR__.'/../vendor/autoload.php';

use App\Kernel;

print_r("Annotations<br /><br />");

$kernel = new Kernel();
$kernel->boot();
$container = $kernel->getContainer();
$kernel->handleRequest();

