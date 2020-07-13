<?php
declare(strict_types=1);
namespace App\Format;

use App\Format\NamedFormatInterface;

class YAML extends BaseFormat implements NamedFormatInterface{
    public function convert()
    {
        $result = '';

        foreach ($this->data as $key => $value) {
            $result .= $key.': '.$value."\n";
        }

        return htmlspecialchars($result);
    }

  public function getName():string
  {
    return 'YAML';
  }
}