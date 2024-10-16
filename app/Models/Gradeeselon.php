<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Gradeeselon extends Model
{
  use HasFactory;

  protected $fillable = ['grade', 'eselon'];

  public function employees(): BelongsToMany
  {
    return $this->belongsToMany(Employee::class);
  }
}
