<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait FiltersByDepartment
{
  public static function scopeForUserDepartment(Builder $query): Builder
  {
    $user = auth()->user();

    // Apply the filter only if the user is not an admin
    if (!$user->is_admin) {
      $query->where('department_id', $user->department_id);
    }

    return $query;
  }
}
