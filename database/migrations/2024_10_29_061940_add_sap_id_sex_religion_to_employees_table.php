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
    Schema::table('employees', function (Blueprint $table) {
      $table->string('sap_id')->unique()->nullable()->after('outlet_id');
      $table->string('religion', length: 10)->nullable()->after('sex');
      $table->string('blood_type', length: 2)->nullable()->after('phone_no');
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::table('employees', function (Blueprint $table) {
      $table->update(['sap_id' => null]);
      $table->dropColumn('religion');
      $table->dropColumn('blood_type');
    });
  }
};
