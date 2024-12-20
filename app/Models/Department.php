<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Department extends Model
{
  use HasFactory;

  protected $fillable = ['id', 'name', 'branch_name'];

  public function outlets(): HasMany
  {
    return $this->hasMany(Outlet::class);
  }
  public function employees(): HasMany
  {
    return $this->hasMany(Employee::class);
  }

  public function users(): HasMany
  {
    return $this->hasMany(User::class);
  }
}
