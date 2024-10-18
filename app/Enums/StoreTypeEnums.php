<?php

namespace App\Enums;

enum StoreTypeEnums: string
{

  case superstore = 'Super Store';
  case basichealth = 'Basic Health';
  case child = 'Child';
  case healthncare = 'Health and Care';
  case medical = 'Medical';
  case new = 'New';
  case none = 'None';

  public function label(): string
  {
    return match ($this) {
      self::superstore => 'Super Store',
      self::basichealth => 'Basic Health',
      self::child => 'Child',
      self::healthncare => 'Health and Care',
      self::medical => 'Medical',
      self::new => 'New',
      self::none => 'None',
    };
  }

  // Optional: Get all cases as an array
  public static function options(): array
  {
    return array_column(self::cases(), 'value', 'name');
  }
}
