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
    $this->call(ProvinceSeeder::class);
    $this->call(PostcodeSeeder::class);
    $this->call(BankSeeder::class);

    $this->call(BandSeeder::class);
    $this->call(DescstatusSeeder::class);
    $this->call(GradeeselonSeeder::class);
    $this->call(RecruitmentSeeder::class);
    $this->call(EmplevelSeeder::class);
    $this->call(AreaSeeder::class);
    $this->call(EmployeeStatusSeeder::class);
    $this->call(TitleSeeder::class);
    $this->call(SubtitleSeeder::class);

    $this->call(DepartmentSeeder::class);
    $this->call(OutletSeeder::class);
  }
}
