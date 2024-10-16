<?php

namespace App\Enums;

enum FemalePantsSizeEnums: string
{
  case SIZE_6 = '6';
  case SIZE_8 = '8';
  case SIZE_10 = '10';
  case SIZE_12 = '12';
  case SIZE_14 = '14';

  public function label(): string
  {
    return 'UK ' . $this->value;
  }

  public static function options(): array
  {
    return array_column(self::cases(), 'value', 'name');
  }
}
