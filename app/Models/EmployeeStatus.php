<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class EmployeeStatus extends Model
{
  use HasFactory;

  protected $fillable = ['name'];

  public function employees(): BelongsToMany
  {
    return $this->belongsToMany(Employee::class);
  }
}
