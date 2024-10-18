<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Outlet extends Model
{
  use HasFactory;

  protected $guarded = [''];

  public function department(): BelongsTo
  {
    return $this->belongsTo(Department::class);
  }
  public function postcode(): BelongsTo
  {
    return $this->belongsTo(Postcode::class);
  }

  public function employees(): HasMany
  {
    return $this->hasMany(Employee::class);
  }
}
