<?php

namespace Database\Seeders;

use App\Models\Area;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AreaSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    Area::insert([
      ['id' => 1, 'name' => 'Kantor BM'],
      ['id' => 2, 'name' => 'Kantor Pusat'],
      ['id' => 3, 'name' => 'Apotek'],
    ]);
  }
}
