<?php

namespace App\Policies;

use App\Models\User;

class OutletPolicy
{
  /**
   * Create a new policy instance.
   */
  public function __construct()
  {
    //
  }
  public function viewAny(User $user)
  {
    return $user->isAdmin(); // Only admins can view the department resource
  }
}
