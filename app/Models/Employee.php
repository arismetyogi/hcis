<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Employee extends Model
{
  use HasFactory;

  protected $fillable = [
    "department_id",
    "nik",
    "first_name",
    "last_name",
    "date_of_birth",
    "phone_no",
    "sex",
    "address",
    "postcode_id",
    "npwp",
    "employee_status_id",
    "title_id",
    "subtitle_id",
    "band_id",
    "outlet_id",
    "npp",
    "gradeeselon_id",
    "area_id",
    "emplevel_id",
    "saptitle_id",
    "saptitle_name",
    "date_hired",
    "date_promoted",
    "date_last_mutated",
    "descstatus_id",
    "bpjs_id",
    "insured_member_count",
    "bpjs_class",
    "bpjstk_id",
    "contract_document_id",
    "contract_sequence_no",
    "contract_term",
    "contract_start",
    "contract_end",
    "status_pasangan",
    "jumlah_tanggungan",
    "pasangan_ditanggung_pajak",
    "rekening_no",
    "rekening_name",
    "bank_id",
    "recruitment_id",
    "pants_size",
    "shirt_size",
    "blood_type",
    "religion",
    "sap_id",
  ];

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

  public function area(): BelongsTo
  {
    return $this->belongsTo(Area::class);
  }
}
