<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    User::create([
      'name' => 'Yogi Arismet',
      'email' => 'arism@email.com',
      'password' => Hash::make('123'),
      'is_admin' => true,
      'outlet_id' => 1,
    ]);

    User::factory()->count(9)->create();
  }
}
