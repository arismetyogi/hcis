<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Employee extends Model
{
  use HasFactory;

  protected $guarded = [];

  public function department(): BelongsTo
  {
    return $this->belongsTo(Department::class);
  }
  public function outlet(): BelongsTo
  {
    return $this->belongsTo(Outlet::class);
  }

  public function payrolls(): HasMany
  {
    return $this->hasMany(Payroll::class);
  }
  public function postcode(): BelongsTo
  {
    return $this->belongsTo(Postcode::class);
  }
  public function employee_status(): BelongsTo
  {
    return $this->belongsTo(EmployeeStatus::class);
  }
  public function gradeeselon(): BelongsTo
  {
    return $this->belongsTo(Gradeeselon::class);
  }
  public function title(): BelongsTo
  {
    return $this->belongsTo(Title::class);
  }
  public function subtitle(): BelongsTo
  {
    return $this->belongsTo(Subtitle::class);
  }
  public function band(): BelongsTo
  {
    return $this->belongsTo(Band::class);
  }
  public function emplevel(): BelongsTo
  {
    return $this->belongsTo(Emplevel::class);
  }
  public function descstatus(): BelongsTo
  {
    return $this->belongsTo(Descstatus::class);
  }
  public function bank(): BelongsTo
  {
    return $this->belongsTo(Bank::class);
  }
  public function recruitment(): BelongsTo
  {
    return $this->belongsTo(Recruitment::class);
  }
  // public function user(): BelongsTo
  // {
  //   return $this->belongsTo(User::class);
  // }
  public function area(): BelongsTo
  {
    return $this->belongsTo(Area::class);
  }
}
