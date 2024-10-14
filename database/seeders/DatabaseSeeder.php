<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
  /**
   * Seed the application's database.
   */
  public function run(): void
  {
    // User::factory(10)->create();
    $this->call(UserSeeder::class);
    // to use custom seeder
    $this->call(CountrySeeder::class);
    $this->call(StateSeeder::class);
    $this->call(CitiesTableChunkOneSeeder::class);
    $this->call(CitiesTableChunkTwoSeeder::class);
    $this->call(CitiesTableChunkThreeSeeder::class);
    $this->call(CitiesTableChunkFourSeeder::class);
    $this->call(CitiesTableChunkFiveSeeder::class);
  }
}
