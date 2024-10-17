<?php

namespace Database\Seeders;

use App\Models\Band;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BandSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    Band::insert([
      ['id' => 1, 'name' => 'BOC'],
      ['id' => 2, 'name' => 'STAFF BOC'],
      ['id' => 3, 'name' => 'BOD-1'],
      ['id' => 4, 'name' => 'BOD-2'],
      ['id' => 5, 'name' => 'BOD-3'],
      ['id' => 6, 'name' => 'BOD-4'],
      ['id' => 7, 'name' => 'BOD-5'],
    ]);
  }
}
