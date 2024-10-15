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
    Schema::create('employees', function (Blueprint $table) {
      $table->id();
      $table->foreignId('department_id')->constrained()->cascadeOnDelete();
      $table->char('NIK', 16);
      $table->string('first_name');
      $table->string('middle_name');
      $table->string('last_name');
      $table->string('city_of_birth');
      $table->date('date_of_birth');
      $table->char('phone_no', 15);
      $table->string('sex');
      $table->string('address');
      $table->foreignId('postcode_id');
      $table->char('npwp');
      $table->foreignId('employee_status_id')->constrained()->cascadeOnDelete();
      $table->foreignId('title_id')->constrained()->cascadeOnDelete();
      $table->foreignId('subtitle_id')->constrained()->cascadeOnDelete();
      $table->foreignId('band_id')->constrained()->cascadeOnDelete();

      $table->foreignId('outlet_id')->constrained()->cascadeOnDelete();
      $table->string('npp', 10);
      $table->foreignId('gradeeselon_id')->constrained()->cascadeOnDelete();
      $table->foreignId('area_id')->constrained()->cascadeOnDelete();
      $table->foreignId('emplevel_id')->constrained()->cascadeOnDelete();
      $table->char('saptitle_id');
      $table->char('saptitle_name');
      $table->date('date_hired');
      $table->date('date_promoted');
      $table->date('date_last_mutated');
      $table->foreignId('descstatus_id')->constrained()->cascadeOnDelete();

      $table->char('bpjs_id');
      $table->integer('insured_member_count');
      $table->integer('bpjs_class');
      $table->integer('bpjstk_id');

      $table->string('contract_document_id');
      $table->integer('contract_sequence_no');
      $table->integer('contract_term');
      $table->date('contract_start');
      $table->date('contract_end');

      $table->string('status_pasangan'); // TK, K-0, K-1, K-2, K-3
      $table->integer('jumlah_tanggungan'); // (0-3)
      $table->string('pasangan_ditanggung_pajak'); // (ya/tidak)

      $table->integer('honorarium');
      $table->char('rekening_no', 16);
      $table->string('rekening_name');
      $table->foreignId('bank_id')->constrained()->cascadeOnDelete();

      $table->foreignId('recruitment_id')->constrained()->cascadeOnDelete();

      $table->string('pants_size');
      $table->string('shirt_size');

      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('employees');
  }
};
