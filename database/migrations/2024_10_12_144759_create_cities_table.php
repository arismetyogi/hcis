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
    Schema::create('cities', function (Blueprint $table) {
      $table->id();
      $table->foreignId('country_id')->constrained()->cascadeOnDelete();
      $table->foreignId('state_id')->constrained()->cascadeOnDelete();
      $table->string('name');
      $table->string('state_code');
      $table->string('country_code');
      $table->string('latitude')->nullable();
      $table->string('longitude')->nullable();
      $table->timestamps();
      $table->boolean('flag')->default(false);
      $table->string('wikiDataId')->nullable();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('cities');
  }
};
