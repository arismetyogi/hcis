<?php

namespace App\Enums;

enum StatusPasanganEnums: string
{
  case tk = 'TK';

  case k0 = 'K-0';
  case k1 = 'K-1';
  case k2 = 'K-2';
  case k3 = 'K-3';

  public function label(): string
  {
    return match ($this) {
      self::tk => 'TK',
      self::k0 => 'K-0',
      self::k1 => 'K-1',
      self::k2 => 'K-2',
      self::k3 => 'K-3',
    };
  }

  // Optional: Get all cases as an array
  public static function options(): array
  {
    return array_column(self::cases(), 'value', 'name');
  }
}
