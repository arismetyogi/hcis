<?php

namespace Database\Seeders;

use App\Models\Bank;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BankSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  { {
      Bank::insert([
        [
          'code' => 'BMRI',
          'name' => 'Bank Mandiri'
        ],
        [
          'code' => 'BNI',
          'name' => 'Bank Negara Indonesia (BNI)'
        ],
        [
          'code' => 'BRI',
          'name' => 'Bank Rakyat Indoesia (BRI)'
        ],
        [
          'code' => 'BTN',
          'name' => 'Bank Tabungan Negara (BTN)'
        ],
        [
          'code' => 'BCA',
          'name' => 'Bank Central Asia (BCA)'
        ],
        [
          'code' => 'BJB',
          'name' => 'Bank Jabar Banten (BJB)'
        ],
        [
          'code' => 'BDKI',
          'name' => 'Bank DKI'
        ],
        [
          'code' => 'DNM',
          'name' => 'Bank Danamon'
        ],
        [
          'code' => 'CIMB',
          'name' => 'Bank CIMB Niaga'
        ],
        [
          'code' => 'PNN',
          'name' => 'Bank Panin'
        ],
        [
          'code' => 'NISP',
          'name' => 'Bank OCBC NISP'
        ]
      ]);
    }
  }
}
