<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use mysql_xdevapi\Table;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //[
        DB::table('users')->insert([
            [
                'role_id'=> 7, //IT Initiator
                'name'=> 'Joseph Kashikite',
                'phone_number'=> '260770366053',
                'email'=>'joseph@geepay.co.zm',
                'password'=> Hash::make(value:'GeePay.1234'),
                'email_verified_at' => now(),
                'is_active' => 1,
                'is_deleted' => 0,
                'created_at'=>now(),
                'updated_at'=> now()
            ],
            [
                'role_id'=> 8, //IT Authorizer
                'name'=> 'Chipoya Kaoma',
                'phone_number'=> '260770366053',
                'email'=>'chipoya@geepay.co.zm',
                'password'=> Hash::make(value:'GeePay.1234'),
                'email_verified_at' => now(),
                'is_active' => 1,
                'is_deleted' => 0,
                'created_at'=>now(),
                'updated_at'=> now()
            ],
            [
                'role_id'=> 1, //System Administrator
                'name'=> 'Blessmore Mulenga',
                'phone_number'=> '260975020473',
                'email'=>'blessmore@geepay.co.zm',
                'password'=> Hash::make(value:'GeePay.1234!!!!'),
                'email_verified_at' => now(),
                'is_active' => 1,
                'is_deleted' => 0,
                'created_at'=>now(),
                'updated_at'=> now()
            ],
            [
                'role_id'=> 1, //System Administrator
                'name'=> 'Dennis Zitha',
                'phone_number'=> '260970669350',
                'email'=>'dennis@geepay.co.zm',
                'password'=> Hash::make(value:'GeePay.1234!!!!'),
                'email_verified_at' => now(),
                'is_active' => 1,
                'is_deleted' => 0,
                'created_at'=>now(),
                'updated_at'=> now()
            ],
            [
                'role_id'=> 1, //System Administrator
                'name'=> 'Mundi Moonga',
                'phone_number'=> '260970606961',
                'email'=>'mundi@geepay.co.zm',
                'password'=> Hash::make(value:'GeePay.1234'),
                'email_verified_at' => now(),
                'is_active' => 1,
                'is_deleted' => 0,
                'created_at'=>now(),
                'updated_at'=> now()
            ],
            [
                'role_id'=> 3, //Operations Authorizer
                'name'=> 'Sheila Liboma',
                'phone_number'=> '260978109930',
                'email'=>'sheila@geepay.co.zm',
                'password'=> Hash::make(value:'GeePay.1234'),
                'email_verified_at' => now(),
                'is_active' => 1,
                'is_deleted' => 0,
                'created_at'=>now(),
                'updated_at'=> now()
            ],
            [
                'role_id'=> 1, //System Administrator
                'name'=> 'Ivor Muluba',
                'phone_number'=> '260977777672',
                'email'=>'ivor@geepay.co.zm',
                'password'=> Hash::make(value:'GeePay.1234'),
                'email_verified_at' => now(),
                'is_active' => 1,
                'is_deleted' => 0,
                'created_at'=>now(),
                'updated_at'=> now()
            ],
            [
                'role_id'=> 2, //Operations Initiator
                'name'=> 'Maria Chimamu',
                'phone_number'=> '260964044884',
                'email'=>'maria@geepay.co.zm',
                'password'=> Hash::make(value:'GeePay.1234'),
                'email_verified_at' => now(),
                'is_active' => 1,
                'is_deleted' => 0,
                'created_at'=>now(),
                'updated_at'=> now()
            ],
            [
                'role_id'=> 5, //Finance Confirmer
                'name'=> 'Chanda Chintu',
                'phone_number'=> '260964044884',
                'email'=>'chanda@geepay.co.zm',
                'password'=> Hash::make(value:'GeePay.1234'),
                'email_verified_at' => now(),
                'is_active' => 1,
                'is_deleted' => 0,
                'created_at'=>now(),
                'updated_at'=> now()
            ],
            [
                'role_id'=> 4, //Finance Initiator
                'name'=> 'Kasao Kanyantha',
                'phone_number'=> '260964044884',
                'email'=>'kasao@geepay.co.zm',
                'password'=> Hash::make(value:'GeePay.1234'),
                'email_verified_at' => now(),
                'is_active' => 1,
                'is_deleted' => 0,
                'created_at'=>now(),
                'updated_at'=> now()
            ]
    ]);
    }
}
