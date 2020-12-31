<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        DB::table('users')->insert([
            ['staff_code' => 'NT0001','name' => 'Nguyen Van A','location_id' => '1','date_of_birth' => '1995-8-23',
                'joined_date' => '2020-10-10', 'gender' => '0','is_admin' => true,'email' => 'admin1@gmail.com',
                'password' => Hash::make('123456789') ],

            ['staff_code' => 'NT0002','name' => 'Tran Van B','location_id' => '1','date_of_birth' => '1996-5-24',
                'joined_date' => '2020-10-10', 'gender' => '0','is_admin' => false,'email' => 'staff2@gmail.com',
                'password' => Hash::make('123456789') ],

            array('staff_code' => 'NT0003','name' => 'Bui Thi C','location_id' => '1','date_of_birth' => '1992-2-23',
                'joined_date' => '2020-10-10', 'gender' => '1','is_admin' => false,
                'email' => 'Huy.NguyenHoang@nashtechglobal.com', 'password' => Hash::make('123456789')),

            ['staff_code' => 'NT0004','name' => 'Truong Thanh D','location_id' => '2','date_of_birth' => '1994-6-18',
                'joined_date' => '2020-10-10', 'gender' => '0','is_admin' => true,
                'email' => 'nguyenhoanghuy250919@gmail.com', 'password' => Hash::make('123456789') ]
        ]);
    }
}
