<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Province extends Model
{
  use HasFactory;

  protected $fillable = ['name', 'name_en', 'province_code'];

  public function postcodes(): HasMany
  {
    return $this->hasMany(Postcode::class, 'code', 'province_code');
  }
}
