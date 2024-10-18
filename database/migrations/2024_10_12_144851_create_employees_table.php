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
      $table->foreignId('team_id')
        ->constrained('teams')
        ->cascadeOnDelete();
      $table->foreignId('department_id')
        ->constrained('departments')
        ->cascadeOnDelete();
      $table->char('NIK')->unique();
      $table->string('first_name');
      $table->string('middle_name')->nullable();
      $table->string('last_name');
      $table->date('date_of_birth');
      $table->char('phone_no', 15);
      $table->string('sex');
      $table->string('address');
      $table->char('postcode_id')->nullable();
      $table->char('npwp');
      $table->foreignId('employee_status_id')
        ->constrained('employee_statuses');
      $table->foreignId('title_id')
        ->constrained('titles')
        ->cascadeOnDelete();
      $table->foreignId('subtitle_id')
        ->constrained('subtitles')
        ->cascadeOnDelete();
      $table->foreignId('band_id')
        ->constrained('bands')
        ->cascadeOnDelete();

      $table->foreignId('outlet_id')
        ->constrained('outlets')
        ->cascadeOnDelete();
      $table->string('npp');
      $table->foreignId('gradeeselon_id')
        ->constrained('gradeeselons')
        ->cascadeOnDelete();
      $table->foreignId('area_id')
        ->constrained('areas')
        ->cascadeOnDelete();
      $table->foreignId('emplevel_id')
        ->constrained('emplevels')
        ->cascadeOnDelete();
      $table->char('saptitle_id')->unique();
      $table->char('saptitle_name');
      $table->date('date_hired');
      $table->date('date_promoted');
      $table->date('date_last_mutated');
      $table->foreignId('descstatus_id')
        ->constrained('descstatuses')
        ->cascadeOnDelete();

      $table->char('bpjs_id')->unique();
      $table->integer('insured_member_count');
      $table->integer('bpjs_class');
      $table->integer('bpjstk_id');

      $table->string('contract_document_id')->nullable();
      $table->integer('contract_sequence_no')->nullable();
      $table->integer('contract_term')->nullable();
      $table->date('contract_start')->nullable();
      $table->date('contract_end')->nullable();

      // $table->string('tax_id');
      $table->string('status_pasangan'); // TK, K-0, K-1, K-2, K-3
      $table->integer('jumlah_tanggungan'); // (0-3)
      $table->string('pasangan_ditanggung_pajak'); // (ya/tidak)

      // $table->integer('honorarium');
      $table->char('rekening_no', 16)->unique();
      $table->string('rekening_name');
      $table->foreignId('bank_id')
        ->constrained('banks');

      $table->foreignId('recruitment_id')
        ->constrained('recruitments')
        ->cascadeOnDelete();

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
