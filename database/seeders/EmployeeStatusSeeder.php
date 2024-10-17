<?php

namespace Database\Seeders;

use App\Models\EmployeeStatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EmployeeStatusSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    EmployeeStatus::insert([
      ['id' => 1, 'name' => 'PKWT'],
      ['id' => 2, 'name' => 'PKWTT'],
      ['id' => 3, 'name' => 'Magang Bersertifikat'],
    ]);
  }
}
