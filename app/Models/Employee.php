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
  public function team(): BelongsTo
  {
    return $this->belongsTo(Team::class);
  }
  public function outlet(): BelongsTo
  {
    return $this->belongsTo(Outlet::class);
  }

  public function payrolls(): HasMany
  {
    return $this->hasMany(Payroll::class);
  }
  public function postcode(): HasOne
  {
    return $this->hasOne(Postcode::class);
  }
  public function employee_status(): BelongsTo
  {
    return $this->belongsTo(EmployeeStatus::class);
  }
  public function gradeeselon(): BelongsTo
  {
    return $this->belongsTo(Gradeeselon::class);
  }
  public function title(): HasOne
  {
    return $this->hasOne(Title::class);
  }
  public function subtitle(): HasOne
  {
    return $this->hasOne(Subtitle::class);
  }
  public function band(): HasOne
  {
    return $this->hasOne(Band::class);
  }
  public function emplevel(): HasOne
  {
    return $this->hasOne(Emplevel::class);
  }
  public function descstatus(): HasOne
  {
    return $this->hasOne(Descstatus::class);
  }
  public function bank(): HasOne
  {
    return $this->hasOne(Bank::class);
  }
  public function recruitment(): BelongsTo
  {
    return $this->belongsTo(Recruitment::class);
  }
  public function user(): BelongsTo
  {
    return $this->belongsTo(User::class);
  }
  public function area(): BelongsTo
  {
    return $this->belongsTo(Area::class);
  }
}
