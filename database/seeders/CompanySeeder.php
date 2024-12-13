<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Company;

class CompanySeeder extends Seeder
{
    public function run()
    {
        Company::create([
            'company_name' => 'コカコーラ',
            'street_address' => '東京都新宿区',
            'representative_name' => '山田太郎',
        ]);

        Company::create([
            'company_name' => 'カルビー',
            'street_address' => '東京都新宿区',
            'representative_name' => '山田太郎',
        ]);
    }
}
