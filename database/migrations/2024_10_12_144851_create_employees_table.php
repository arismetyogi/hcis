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
    if (! Schema::hasTable('employees')) {
      Schema::create('employees', function (Blueprint $table) {
        $table->id();
        $table->foreignId('department_id')
          ->constrained('departments')
          ->cascadeOnDelete();
        $table->string('nik', length: 16)->unique();
        $table->string('first_name', 50);
        $table->string('last_name', 50);
        $table->date('date_of_birth');
        $table->string('phone_no', 15);
        $table->string('sex', 9);
        $table->string('address', 200);
        $table->unsignedBigInteger('postcode_id')->nullable();
        $table->string('npwp', length: 16);
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
        $table->string('npp', length: 9);
        $table->foreignId('gradeeselon_id')
          ->constrained('gradeeselons')
          ->cascadeOnDelete();
        $table->foreignId('area_id')
          ->constrained('areas')
          ->cascadeOnDelete();
        $table->foreignId('emplevel_id')
          ->constrained('emplevels')
          ->cascadeOnDelete();
        $table->string('saptitle_id', 50)->unique();
        $table->string('saptitle_name', 100);
        $table->date('date_hired');
        $table->date('date_promoted');
        $table->date('date_last_mutated');
        $table->foreignId('descstatus_id')
          ->constrained('descstatuses')
          ->cascadeOnDelete();

        $table->string('bpjs_id', 16)->unique();
        $table->integer('insured_member_count');
        $table->integer('bpjs_class');
        $table->string('bpjstk_id', 16);

        $table->string('contract_document_id', 100)->nullable();
        $table->integer('contract_sequence_no')->nullable();
        $table->integer('contract_term')->nullable();
        $table->date('contract_start')->nullable();
        $table->date('contract_end')->nullable();

        // $table->string('tax_id');
        $table->string('status_pasangan', 3); // TK, K-0, K-1, K-2, K-3
        $table->integer('jumlah_tanggungan'); // (0-3)
        $table->boolean('pasangan_ditanggung_pajak'); // (ya/tidak)

        // $table->integer('honorarium');
        $table->string('rekening_no', 16)->unique();
        $table->string('rekening_name', 50);
        $table->foreignId('bank_id')
          ->constrained('banks');

        $table->foreignId('recruitment_id')
          ->constrained('recruitments')
          ->cascadeOnDelete();

        $table->string('pants_size', 7);
        $table->string('shirt_size', 5);

        $table->timestamps();
      });
    }
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('employees');
  }
};
