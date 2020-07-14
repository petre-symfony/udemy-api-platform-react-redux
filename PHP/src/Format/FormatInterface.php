<?php
namespace App\Format;


interface FormatInterface {
  public function convert();
  public function setData(array $data): void;
}