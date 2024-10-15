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
      $table->string('name');
      $table->char('branch_id');
      $table->string('branch_name');
      $table->char('outlet_sap_id');
      $table->string('store_type');
      $table->date('operational_date')->default(null);
      $table->string('address');
      $table->foreignId('postcode_id')->constrained()->cascadeOnDelete();
      $table->float('latitude');
      $table->float('longitude');
      $table->char('phone_no');
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
