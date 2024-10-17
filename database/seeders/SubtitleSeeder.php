<?php

namespace Database\Seeders;

use App\Models\Subtitle;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SubtitleSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    Subtitle::insert([
      ['id' => 1, 'name' => 'Manager KP'],
      ['id' => 2, 'name' => 'PhM'],
      ['id' => 3, 'name' => 'Asman KP'],
      ['id' => 4, 'name' => 'TTK'],
      ['id' => 5, 'name' => 'Supervisor KP'],
      ['id' => 6, 'name' => 'Pelaksana KP'],
      ['id' => 7, 'name' => 'Staff BM'],
      ['id' => 8, 'name' => 'KTU'],
      ['id' => 9, 'name' => 'BM'],
      ['id' => 10, 'name' => 'Aping'],
    ]);
  }
}
