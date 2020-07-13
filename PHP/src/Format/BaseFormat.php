<?php

namespace App\Format;

abstract class BaseFormat {
    protected $data;

    public function __construct(?string $data="Hello")
    {
        $this->data = $data;
    }

    public function getData()
    {
        return $this->data;
    }

    public function setData(array $data)
    {
        $this->data = $data;
    }

  public abstract function convert();

  public function __toString()
  {
    return $this->convert();
  }
}