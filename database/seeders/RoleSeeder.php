<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('roles')->insert([
            [
                'name' => 'System Administrator',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Operations Initiator',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Operations Authorizer',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Finance Initiator',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Finance Confirmer',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Finance Authorizer',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'IT Initiator',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'IT Authorizer',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Business Administrator',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Business Manager',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Business Viewer',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }
}
