<?php

namespace Database\Seeders;

use App\Models\Gradeeselon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GradeeselonSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    Gradeeselon::insert([
      ['id' => 1, 'grade' => 17, 'eselon' => 'II a'],
      ['id' => 2, 'grade' => 16, 'eselon' => 'II b'],
      ['id' => 3, 'grade' => 15, 'eselon' => 'II c'],
      ['id' => 4, 'grade' => 14, 'eselon' => 'III a'],
      ['id' => 5, 'grade' => 13, 'eselon' => 'III a'],
      ['id' => 6, 'grade' => 12, 'eselon' => 'III b'],
      ['id' => 7, 'grade' => 11, 'eselon' => 'III b'],
      ['id' => 8, 'grade' => 10, 'eselon' => 'IV a'],
      ['id' => 9, 'grade' => 9, 'eselon' => 'IV a'],
      ['id' => 10, 'grade' => 8, 'eselon' => 'IV b'],
      ['id' => 11, 'grade' => 7, 'eselon' => 'IV b'],
      ['id' => 12, 'grade' => 6, 'eselon' => 'Non Eselon'],
      ['id' => 13, 'grade' => 5, 'eselon' => 'Non Eselon'],
      ['id' => 14, 'grade' => 4, 'eselon' => 'Non Eselon'],
      ['id' => 15, 'grade' => 3, 'eselon' => 'Non Eselon'],
    ]);
  }
}
