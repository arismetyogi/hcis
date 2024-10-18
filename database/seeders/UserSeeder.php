<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    // force insert
    DB::statement('SET FOREIGN_KEY_CHECKS=0;');
    User::create([
      'name' => 'Admin',
      'email' => 'admin@admin.com',
      'password' => Hash::make('123'),
      'is_admin' => true,
      'team_id' => 1,
    ]);
    DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    // User::factory()->count(9)->create();
  }
}
