<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /*DB::table('clients')->insert([
           [
               'role_id' => 1,
               'business_id' => 1,
               'name' => 'Isaac Malemelo',
               'email' => 'imalemelo@gmail.com',
               'password' => Hash::make('Yolanta88'),
               'is_email_verified' => 1,
               'is_account_owner' => 1,
               'is_active' => 1
           ]
        ]);*/
    }
}
