<?php

namespace Database\Seeders;

use App\Models\Emplevel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EmplevelSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    Emplevel::insert([
      ['id' => 1, 'name' => 'D1'],
      ['id' => 2, 'name' => 'D2'],
      ['id' => 3, 'name' => 'D3'],
      ['id' => 4, 'name' => 'C1'],
      ['id' => 5, 'name' => 'C2'],
      ['id' => 6, 'name' => 'C3'],
      ['id' => 7, 'name' => 'C4'],
      ['id' => 8, 'name' => 'B1'],
      ['id' => 9, 'name' => 'B2'],
      ['id' => 10, 'name' => 'B3'],
      ['id' => 11, 'name' => 'B4'],
      ['id' => 12, 'name' => 'A1'],
      ['id' => 13, 'name' => 'A2'],
      ['id' => 14, 'name' => 'A3'],
      ['id' => 15, 'name' => 'A4'],
    ]);
  }
}
