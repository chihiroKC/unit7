<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CompaniesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
{
    \DB::table('companies')->insert([
        ['name' => 'メーカーA'],
        ['name' => 'メーカーB'],
        ['name' => 'メーカーC'],
    ]);
}

}
