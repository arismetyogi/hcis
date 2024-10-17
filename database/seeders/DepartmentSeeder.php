<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    Department::insert([
      // ['name' => 'Information Technology', 'branch_id'=>],
      // ['name' => 'Operation Performance', 'branch_id'=>],
      // ['name' => 'Supply Chain', 'branch_id'=>],
      // ['name' => 'Finance', 'branch_id'=>],
      // ['name' => 'Accounting', 'branch_id'=>],
      // ['name' => 'Tax', 'branch_id'=>],
      // ['name' => 'Legal', 'branch_id'=>],
      // ['name' => 'Corporate Communication', 'branch_id'=>],
      // ['name' => 'Procurement', 'branch_id'=>],
      // ['name' => 'Human Resources', 'branch_id'=>],
      // ['name' => 'Relation Management', 'branch_id'=>],
      // ['name' => 'Financial Planning & Analysis', 'branch_id'=>],
      ['name' => 'UB Ambon', 'branch_id' => 3168, 'branch_name' => null],
      ['name' => 'UB Balikpapan', 'branch_id' => 3102, 'branch_name' => null],
      ['name' => 'UB Banda Aceh', 'branch_id' => 3101, 'branch_name' => null],
      ['name' => 'UB Bandung', 'branch_id' => 3103, 'branch_name' => null],
      ['name' => 'UB Bangka Belitung', 'branch_id' => 3169, 'branch_name' => null],
      ['name' => 'UB Banjarmasin', 'branch_id' => 3107, 'branch_name' => null],
      ['name' => 'UB Batam', 'branch_id' => 3108, 'branch_name' => null],
      ['name' => 'UB Bekasi', 'branch_id' => 3110, 'branch_name' => null],
      ['name' => 'UB Bogor', 'branch_id' => 3112, 'branch_name' => null],
      ['name' => 'UB Cilegon', 'branch_id' => 3148, 'branch_name' => null],
      ['name' => 'UB Cirebon', 'branch_id' => 3114, 'branch_name' => null],
      ['name' => 'UB Denpasar', 'branch_id' => 3116, 'branch_name' => null],
      ['name' => 'UB Depok', 'branch_id' => 3113, 'branch_name' => null],
      ['name' => 'UB Gorontalo', 'branch_id' => 3126, 'branch_name' => null],
      ['name' => 'UB Gresik', 'branch_id' => 3144, 'branch_name' => null],
      ['name' => 'UB Jambi', 'branch_id' => 3133, 'branch_name' => null],
      ['name' => 'UB Jaya I', 'branch_id' => 3117, 'branch_name' => null],
      ['name' => 'UB Jaya II', 'branch_id' => 3118, 'branch_name' => null],
      ['name' => 'UB Jayapura', 'branch_id' => 3119, 'branch_name' => null],
      ['name' => 'UB Jember', 'branch_id' => 3120, 'branch_name' => null],
      ['name' => 'UB Karawang', 'branch_id' => 3111, 'branch_name' => null],
      ['name' => 'UB Kendari', 'branch_id' => 3121, 'branch_name' => null],
      ['name' => 'UB Kupang', 'branch_id' => 3122, 'branch_name' => null],
      ['name' => 'UB Lampung', 'branch_id' => 3123, 'branch_name' => null],
      ['name' => 'UB Madura', 'branch_id' => 3171, 'branch_name' => null],
      ['name' => 'UB Makassar', 'branch_id' => 3124, 'branch_name' => null],
      ['name' => 'UB Malang', 'branch_id' => 3125, 'branch_name' => null],
      ['name' => 'UB Manado', 'branch_id' => 3127, 'branch_name' => null],
      ['name' => 'UB Mataram', 'branch_id' => 3129, 'branch_name' => null],
      ['name' => 'UB Medan', 'branch_id' => 3130, 'branch_name' => null],
      ['name' => 'UB Nusadua', 'branch_id' => 3115, 'branch_name' => null],
      ['name' => 'UB Padang', 'branch_id' => 3131, 'branch_name' => null],
      ['name' => 'UB Palangkaraya', 'branch_id' => 3132, 'branch_name' => null],
      ['name' => 'UB Palembang', 'branch_id' => 3134, 'branch_name' => null],
      ['name' => 'UB Palu', 'branch_id' => 3135, 'branch_name' => null],
      ['name' => 'UB Pekalongan', 'branch_id' => 3140, 'branch_name' => null],
      ['name' => 'UB Pekanbaru', 'branch_id' => 3136, 'branch_name' => null],
      ['name' => 'UB Pontianak', 'branch_id' => 3137, 'branch_name' => null],
      ['name' => 'UB Purwokerto', 'branch_id' => 3170, 'branch_name' => null],
      ['name' => 'UB Samarinda', 'branch_id' => 3139, 'branch_name' => null],
      ['name' => 'UB Semarang', 'branch_id' => 3141, 'branch_name' => null],
      ['name' => 'UB Sidoarjo', 'branch_id' => 3145, 'branch_name' => null],
      ['name' => 'UB Sorong', 'branch_id' => 3143, 'branch_name' => null],
      ['name' => 'UB Sukabumi', 'branch_id' => 3147, 'branch_name' => null],
      ['name' => 'UB Surabaya', 'branch_id' => 3146, 'branch_name' => null],
      ['name' => 'UB Surakarta', 'branch_id' => 3142, 'branch_name' => null],
      ['name' => 'UB Tangerang', 'branch_id' => 3149, 'branch_name' => null],
      ['name' => 'UB Tanjung Pinang', 'branch_id' => 3109, 'branch_name' => null],
      ['name' => 'UB Tasikmalaya', 'branch_id' => 3105, 'branch_name' => null],
      ['name' => 'UB Ternate', 'branch_id' => 3128, 'branch_name' => null],
      ['name' => 'UB Yogya', 'branch_id' => 3150, 'branch_name' => null],
    ]);
  }
}
