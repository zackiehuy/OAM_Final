<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class RequestSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        DB::table('request')->insert([
            ['user_id' => '2','assignment_id' => '1','asset_category_id' => null,
            'requested_date' => '2020-10-10','created_by' => '1'],
            ['user_id' => '2','assignment_id' => '2','asset_category_id' => null,
                'requested_date' => '2020-10-11','created_by' => '2'],
            ['user_id' => '2','assignment_id' => null,'asset_category_id' => '1',
                'requested_date' => '2020-10-12','created_by' => '1'],
            ['user_id' => '2','assignment_id' => null,'asset_category_id' => '1',
                'requested_date' => '2020-10-13','created_by' => '2'],
            ['user_id' => '2','assignment_id' => '9','asset_category_id' => null,
                'requested_date' => '2020-10-14','created_by' => '1'],
            ['user_id' => '2','assignment_id' => '1','asset_category_id' => null,
            'requested_date' => '2020-10-10','created_by' => '1']
        ]);
    }
}
