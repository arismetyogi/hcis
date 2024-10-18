<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Postcode extends Model
{
  use HasFactory;

  protected $fillable = ['urban', 'subdsitrict', 'city', 'province_code', 'postal_code'];

  public function province(): BelongsTo
  {
    return $this->belongsTo(Province::class, 'province_code', 'code');
  }

  public function employees(): BelongsToMany
  {
    return $this->belongsToMany(Employee::class);
  }
  public function outlets(): HasMany
  {
    return $this->hasMany(Outlet::class);
  }
}
