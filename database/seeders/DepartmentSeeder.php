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
      ['id' => 1, 'name' => 'Information Technology', 'branch_name' => 'IT'],
      ['id' => 3168, 'name' => 'UB Ambon', 'branch_name' => null],
      ['id' => 3102, 'name' => 'UB Balikpapan', 'branch_name' => null],
      ['id' => 3101, 'name' => 'UB Banda Aceh', 'branch_name' => null],
      ['id' => 3103, 'name' => 'UB Bandung', 'branch_name' => null],
      ['id' => 3169, 'name' => 'UB Bangka Belitung', 'branch_name' => null],
      ['id' => 3107, 'name' => 'UB Banjarmasin', 'branch_name' => null],
      ['id' => 3108, 'name' => 'UB Batam', 'branch_name' => null],
      ['id' => 3110, 'name' => 'UB Bekasi', 'branch_name' => null],
      ['id' => 3112, 'name' => 'UB Bogor', 'branch_name' => null],
      ['id' => 3148, 'name' => 'UB Cilegon', 'branch_name' => null],
      ['id' => 3114, 'name' => 'UB Cirebon', 'branch_name' => null],
      ['id' => 3116, 'name' => 'UB Denpasar', 'branch_name' => null],
      ['id' => 3113, 'name' => 'UB Depok', 'branch_name' => null],
      ['id' => 3126, 'name' => 'UB Gorontalo', 'branch_name' => null],
      ['id' => 3144, 'name' => 'UB Gresik', 'branch_name' => null],
      ['id' => 3133, 'name' => 'UB Jambi', 'branch_name' => null],
      ['id' => 3117, 'name' => 'UB Jaya I', 'branch_name' => null],
      ['id' => 3118, 'name' => 'UB Jaya II', 'branch_name' => null],
      ['id' => 3119, 'name' => 'UB Jayapura', 'branch_name' => null],
      ['id' => 3120, 'name' => 'UB Jember', 'branch_name' => null],
      ['id' => 3111, 'name' => 'UB Karawang', 'branch_name' => null],
      ['id' => 3121, 'name' => 'UB Kendari', 'branch_name' => null],
      ['id' => 3122, 'name' => 'UB Kupang', 'branch_name' => null],
      ['id' => 3123, 'name' => 'UB Lampung', 'branch_name' => null],
      ['id' => 3171, 'name' => 'UB Madura', 'branch_name' => null],
      ['id' => 3124, 'name' => 'UB Makassar', 'branch_name' => null],
      ['id' => 3125, 'name' => 'UB Malang', 'branch_name' => null],
      ['id' => 3127, 'name' => 'UB Manado', 'branch_name' => null],
      ['id' => 3129, 'name' => 'UB Mataram', 'branch_name' => null],
      ['id' => 3130, 'name' => 'UB Medan', 'branch_name' => null],
      ['id' => 3115, 'name' => 'UB Nusadua', 'branch_name' => null],
      ['id' => 3131, 'name' => 'UB Padang', 'branch_name' => null],
      ['id' => 3132, 'name' => 'UB Palangkaraya', 'branch_name' => null],
      ['id' => 3134, 'name' => 'UB Palembang', 'branch_name' => null],
      ['id' => 3135, 'name' => 'UB Palu', 'branch_name' => null],
      ['id' => 3140, 'name' => 'UB Pekalongan', 'branch_name' => null],
      ['id' => 3136, 'name' => 'UB Pekanbaru', 'branch_name' => null],
      ['id' => 3137, 'name' => 'UB Pontianak', 'branch_name' => null],
      ['id' => 3170, 'name' => 'UB Purwokerto', 'branch_name' => null],
      ['id' => 3139, 'name' => 'UB Samarinda', 'branch_name' => null],
      ['id' => 3141, 'name' => 'UB Semarang', 'branch_name' => null],
      ['id' => 3145, 'name' => 'UB Sidoarjo', 'branch_name' => null],
      ['id' => 3143, 'name' => 'UB Sorong', 'branch_name' => null],
      ['id' => 3147, 'name' => 'UB Sukabumi', 'branch_name' => null],
      ['id' => 3146, 'name' => 'UB Surabaya', 'branch_name' => null],
      ['id' => 3142, 'name' => 'UB Surakarta', 'branch_name' => null],
      ['id' => 3149, 'name' => 'UB Tangerang', 'branch_name' => null],
      ['id' => 3109, 'name' => 'UB Tanjung Pinang', 'branch_name' => null],
      ['id' => 3105, 'name' => 'UB Tasikmalaya', 'branch_name' => null],
      ['id' => 3128, 'name' => 'UB Ternate', 'branch_name' => null],
      ['id' => 3150, 'name' => 'UB Yogya', 'branch_name' => null],
    ]);
  }
}
