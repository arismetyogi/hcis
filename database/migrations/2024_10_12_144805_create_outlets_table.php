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
    if (! Schema::hasTable('outlets')) {
      Schema::create('outlets', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('department_id');
        $table->foreign('department_id')
          ->references('id')
          ->on('departments');
        $table->string('outlet_sap_id', 12);
        $table->string('name');
        $table->string('store_type')->nullable();
        $table->date('operational_date')->nullable();
        $table->string('address')->nullable();
        $table->foreignId('postcode_id')->nullable()->constrained('postcodes');
        $table->float('latitude')->nullable();
        $table->float('longitude')->nullable();
        $table->string('phone')->nullable();
        $table->timestamps();
      });
    }
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('outlets');
  }
};
//
