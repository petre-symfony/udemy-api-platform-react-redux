<?php


namespace App;

use App\Format\JSON;
use App\Format\XML;
use App\Format\FormatInterface;

class Kernel
{
  private $container;

  public function __construct()
  {
    $this->container = new Container();
  }

  public function getContainer(): Container{
    return $this->container;
  }

  public function boot(){
    $this->bootContainer($this->container);
  }

  public function bootContainer(Container $container){
    $container->addService('format.json', function() use ($container){
      return new JSON();
    });
    $container->addService('format.xml', function() use ($container){
      return new XML();
    });
    $container->addService('format', function() use ($container){
      return $container->getService('format.json');
    }, FormatInterface::class);

    $container->loadServices('App\\Service');
    $container->loadServices('App\\Controller');
  }
}