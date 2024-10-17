<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  /**
   * Run the migrations.
   */
  public function up(): void
  {
    Schema::create('payrolls', function (Blueprint $table) {
      $table->id();
      $table->foreignId('employee_id')
        ->constrained('employees')
        ->cascadeOnDelete();
      $table->float('1050_honorarium');
      $table->float('uang_saku_mb');
      $table->float('3000_lembur');
      $table->float('2580_tunj_lain');
      $table->float('ujp');
      $table->float('4020_sumbangan_cuti_tahunan');
      $table->float('6500_pot_wajib_koperasi');
      $table->float('6540_pot_pinjaman_koperasi');
      $table->float('6590_pot_ykkkf');
      $table->float('6620_pot_keterlambatan');
      $table->float('6630_pinjaman_karyawan');
      $table->float('6700_pot_bank_mandiri');
      $table->float('6701_pot_bank_bri');
      $table->float('6702_pot_bank_btn');
      $table->float('6703_pot_bank_danamon');
      $table->float('6704_pot_bank_dki');
      $table->float('6705_pot_bank_bjb');
      $table->float('6750_pot_adm_bank_mandiri');
      $table->float('6751_pot_adm_bank_bri');
      $table->float('6752_pot_adm_bank_bjb');
      $table->float('6900_pot_lain');
      $table->char('bln_thn');
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('payrolls');
  }
};
