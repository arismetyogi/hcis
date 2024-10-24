<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class UserSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    // force insert
    $json = File::get(database_path('data/users.json'));
    $users = json_decode($json, true);

    // Loop through each user and insert into the database with hashed passwords
    foreach ($users as $user) {
      DB::statement('SET FOREIGN_KEY_CHECKS=0;');
      User::create([
        'name' => $user['name'],
        'email' => $user['email'],
        'password' => Hash::make($user['password']), // Hash the password
        'is_admin' => $user['is_admin'],
        'department_id' => $user['department_id'],
      ]);
      DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    };
  }
}
