<?php

namespace App\Format;

use App\Format\NamedFormatInterface;

class XML extends BaseFormat implements NamedFormatInterface{
    public function convert()
    {
        $result = '';

        foreach ($this->data as $key => $value) {
            $result .= '<'.$key.'>'.$value.'</'.$key.'>';
        }

        return htmlspecialchars($result);
    }

  public function getName()
  {
    return 'XML';
  }
}