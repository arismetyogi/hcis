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
    Schema::create('outlets', function (Blueprint $table) {
      $table->id();
      $table->char('outlet_sap_id');
      $table->string('name');
      $table->string('branch_id');
      $table->string('branch_name');
      $table->string('store_type')->nullable();
      $table->date('operational_date')->nullable();
      $table->string('address')->nullable();
      $table->foreignId('postcode_id')->nullable()->constrained('postcodes');
      $table->float('latitude')->nullable();
      $table->float('longitude')->nullable();
      $table->char('phone')->nullable();
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('outlets');
  }
};
