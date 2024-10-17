<?php

namespace Database\Seeders;

use App\Models\Title;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TitleSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    Title::insert([
      ['id' => 1, 'name' => 'Komisaris'],
      ['id' => 2, 'name' => 'Direksi'],
      ['id' => 3, 'name' => 'Manager'],
      ['id' => 4, 'name' => 'Asman'],
      ['id' => 5, 'name' => 'Supervisor'],
      ['id' => 6, 'name' => 'Pelaksana'],
      ['id' => 7, 'name' => 'Staff BOC'],
    ]);
  }
}
