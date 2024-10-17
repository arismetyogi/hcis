<?php

namespace Database\Seeders;

use App\Models\Descstatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DescstatusSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    Descstatus::insert([
      ['id' => 1, 'name' => 'Pharmacy Manager'],
      ['id' => 2, 'name' => 'Apoteker Pendamping'],
      ['id' => 3, 'name' => 'TTK'],
      ['id' => 4, 'name' => 'Staff BM'],
      ['id' => 5, 'name' => 'Kantor Pusat'],
    ]);
  }
}
