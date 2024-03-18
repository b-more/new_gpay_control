<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProvinceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('provinces')->insert([
            [
                'country_id' => 1,
                'name' => 'Central Province'
            ],
            [
                'country_id' => 1,
                'name' => 'Copperbelt Province'
            ],
            [
                'country_id' => 1,
                'name' => 'Eastern Province'
            ],
            [
                'country_id' => 1,
                'name' => 'Luapula Province'
            ],
            [
                'country_id' => 1,
                'name' => 'Lusaka Province'
            ],
            [
                'country_id' => 1,
                'name' => 'Muchinga Province'
            ],
            [
                'country_id' => 1,
                'name' => 'Northern Province'
            ],
            [
                'country_id' => 1,
                'name' => 'Northern Western Province'
            ],
            [
                'country_id' => 1,
                'name' => 'Southern Province'
            ],
            [
                'country_id' => 1,
                'name' => 'Western Province'
            ]
        ]);
    }
}
