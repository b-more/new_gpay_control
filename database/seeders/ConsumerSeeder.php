<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ConsumerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('consumers')->insert([
            [
                'name'=> 'Dennis Zitha',
                'email' => 'denniszitha@gmail.com',
                'phone_number' => '260979669350',
                'password' => Hash::make('1111'),
                'gender' => 'male',
                'qr_code' => 'QR2342397805',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name'=> 'Blessmore Mulenga',
                'email' => 'mulengablessmore@gmail.com',
                'phone_number' => '260975020473',
                'password' => Hash::make('1111'),
                'gender' => 'male',
                'qr_code' => 'QR2342397806',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name'=> 'Isaac Raj',
                'email' => 'imalemelo@gmail.com',
                'phone_number' => '260779205949',
                'password' => Hash::make('1111'),
                'gender' => 'male',
                'qr_code' => 'QR2342397807',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name'=> 'Yolanta Raj',
                'email' => 'yolantakays@gmail.com',
                'phone_number' => '260978826129',
                'password' => Hash::make('1111'),
                'gender' => 'female',
                'qr_code' => 'QR2342397808',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);

    }
}
