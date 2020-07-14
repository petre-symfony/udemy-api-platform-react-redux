<?php
namespace App;


class Container
{
  private $services = [];
  private $aliases = [];

  public function addService(
    string $name,
    \Closure $closure,
    ?string $alias = null
  ): void{
    $this->services[$name] = $closure;

    if($alias){
      $this->addAlias($alias, $name);
    }
  }

  public function addAlias(string $alias, string $service): void{
    $this->aliases[$alias] = $service;
  }

  public function hasService(string $name):bool {
    return isset($this->services[$name]);
  }

  public function hasAlias(string $name):bool {
    return isset($this->aliases[$name]);
  }

  public function getService(string $name){
    if(!$this->hasService($name)){
      return null;
    }
    if($this->services[$name] instanceof \Closure){
      $this->services[$name] = $this->services[$name]();
    }

    return $this->services[$name];
  }

  public function getAlias(string $name){
    return $this->getService($this->aliases[$name]);
  }

  public function getServices(): array {
    return [
      'services' => array_keys($this->services),
      'aliases' => $this->aliases
    ];
  }

  public function loadServices(string $namespace){
    $baseDir = __DIR__.'/';

    $actualDir = str_replace('\\', '/', $namespace);
    $actualDir = $baseDir . substr(
      $actualDir,
      strpos($actualDir, '/') + 1
    );

    $files = array_filter(scandir($actualDir), function($file){
      return $file !== '.' && $file !== '..';
    });
    var_dump($files);
  }
}