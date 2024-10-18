<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payroll extends Model
{
  use HasFactory;

  protected $guarded = ['id'];

  public function employee(): BelongsTo
  {
    return $this->belongsTo(Employee::class);
  }

  // Define an Indirect Relationship in the Payroll Model: You can define a method in the Payroll model to access the Department through the Employee relationship:
  public function department()
  {
    return $this->hasOneThrough(
      Department::class,
      Employee::class,
      'id',           // Foreign key on the Employee table
      'id',           // Foreign key on the Department table
      'employee_id',  // Local key on the Payroll table
      'department_id' // Local key on the Employee table
    );
  }
}
