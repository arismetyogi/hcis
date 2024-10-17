<?php

namespace Database\Seeders;

use App\Models\Recruitment;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RecruitmentSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    Recruitment::insert([
      ['id' => 1, 'name' => 'Pembukaan Apotek Baru'],
      ['id' => 2, 'name' => 'Pengganti Pegawai Resign'],
      ['id' => 3, 'name' => 'Pengganti Pegawai Pensiun'],
    ]);
  }
}
