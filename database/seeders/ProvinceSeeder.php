<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ProvinceSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    $jsonPath = database_path('/data/provinces_array.json');

    // Check if the JSON file exists
    if (File::exists($jsonPath)) {
      // Read the file
      $jsonData = File::get($jsonPath);

      // Decode the JSON data
      $provinces = json_decode($jsonData, true);

      // Insert data into the 'poscodes' table
      if (is_array($provinces)) {
        DB::table('provinces')->insert($provinces);
      }
    } else {
      $this->command->error("JSON file not found at {$jsonPath}");
    }
  }
}
