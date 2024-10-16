<?php

namespace App\Enums;

enum MalePantsSizeEnums: string
{
  case SIZE_28 = '28';
  case SIZE_30 = '30';
  case SIZE_32 = '32';
  case SIZE_34 = '34';
  case SIZE_36 = '36';

  public function label(): string
  {
    return $this->value . " Inch";
  }

  public static function options(): array
  {
    return array_column(self::cases(), 'value', 'name');
  }
}
