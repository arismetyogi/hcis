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

      $table->unsignedBigInteger('employee_id');
      $table->foreign('employee_id')
        ->references('id')
        ->on('employees')
        ->onDelete('cascade');

      $table->float('1050_honorarium')
        ->default(0);
      $table->float('uang_saku_mb')
        ->nullable();
      $table->float('3000_lembur')
        ->nullable();
      $table->float('2580_tunj_lain')
        ->nullable();
      $table->float('ujp')
        ->nullable();
      $table->float('4020_sumbangan_cuti_tahunan')
        ->nullable();
      $table->float('6500_pot_wajib_koperasi')
        ->nullable();
      $table->float('6540_pot_pinjaman_koperasi')
        ->nullable();
      $table->float('6590_pot_ykkkf')
        ->nullable();
      $table->float('6620_pot_keterlambatan')
        ->nullable();
      $table->float('6630_pinjaman_karyawan')
        ->nullable();
      $table->float('6700_pot_bank_mandiri')
        ->nullable();
      $table->float('6701_pot_bank_bri')
        ->nullable();
      $table->float('6702_pot_bank_btn')
        ->nullable();
      $table->float('6703_pot_bank_danamon')
        ->nullable();
      $table->float('6704_pot_bank_dki')
        ->nullable();
      $table->float('6705_pot_bank_bjb')
        ->nullable();
      $table->float('6750_pot_adm_bank_mandiri')
        ->nullable();
      $table->float('6751_pot_adm_bank_bri')
        ->nullable();
      $table->float('6752_pot_adm_bank_bjb')
        ->nullable();
      $table->float('6900_pot_lain')
        ->nullable();
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
