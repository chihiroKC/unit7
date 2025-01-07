<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Company;

class CompanySeeder extends Seeder
{
    public function run()
    {

        // 外部キー制約を無効化
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Company::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;'); // 制約を再度有効化


        Company::create([
            'company_name' => 'コカコーラ',
            'street_address' => '東京都新宿区',
            'representative_name' => '山田太郎',
        ]);

        Company::create([
            'company_name' => 'カルビー',
            'street_address' => '宮城県仙台市',
            'representative_name' => '仙田台二郎',
        ]);

        Company::create([
            'company_name' => 'KangCoffee',
            'street_address' => '山形県寒河江市',
            'representative_name' => '黒田千寛',
        ]);
    }
}
