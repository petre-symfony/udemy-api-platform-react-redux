<?php

namespace App\Format;

use App\Format\FromStringInterface;

class JSON extends BaseFormat implements FromStringInterface {
    public function convert()
    {
        return json_encode($this->data);
    }

  public function convertFromString($string)
  {
    return json_decode($string, true);
  }


}