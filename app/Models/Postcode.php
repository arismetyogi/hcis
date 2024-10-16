<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Postcode extends Model
{
  use HasFactory;

  protected $guarded = [];

  public function province(): BelongsTo
  {
    return $this->belongsTo(Province::class);
  }

  public function employees(): BelongsToMany
  {
    return $this->belongsToMany(Employee::class);           
  }
}
