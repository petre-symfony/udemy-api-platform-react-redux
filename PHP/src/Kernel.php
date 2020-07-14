<?php


namespace App;

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

  public function boot(){}

  public function bootContainer(Container $container){
    
  }
}