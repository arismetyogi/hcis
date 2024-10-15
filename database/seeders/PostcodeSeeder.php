<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PostcodeSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    $jsonPath = database_path('/data/postal_array.json');

    // Check if the JSON file exists
    if (File::exists($jsonPath)) {
      // Read the file
      if (File::exists($jsonPath)) {
        $jsonData = File::get($jsonPath);
        $postcodes = json_decode($jsonData, true);

        if (is_array($postcodes)) {
          // Split the data into smaller chunks
          $chunks = array_chunk($postcodes, 500); // Adjust chunk size as needed

          foreach ($chunks as $chunk) {
            DB::table('postcodes')->insert($chunk);
          }
        }
      } else {
        $this->command->error("JSON file not found at {$jsonPath}");
      }
    }
  }
}
