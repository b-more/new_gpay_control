<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BusinessCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('business_categories')->insert([
            [
                'name' => 'Art & Graphics'
            ],
            [
                'name' => 'Construction'
            ],
            [
                'name' => 'Ecommerce'
            ],
            [
                'name' => 'Entertainment'
            ],
            [
                'name' => 'Finance'
            ],
            [
                'name' => 'School'
            ],
            [
                'name' => 'Technology'
            ],
            [
                'name' => 'Transport & Logistics'
            ]
        ]);
    }
}
