<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AssetSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        DB::table('asset')->insert([
            ['asset_code' => 'A0001','location_id' => '1','asset_category_id' => '1',
            'name' => 'A','description' => '','installed_date' => '2020-10-10',
                ],

            ['asset_code' => 'A0002','location_id' => '1','asset_category_id' => '2',
            'name' => 'B','description' => '','installed_date' => '2020-10-10',
                ],

            ['asset_code' => 'A0003','location_id' => '1','asset_category_id' => '3',
            'name' => 'C','description' => '','installed_date' => '2020-10-10',
                ],

            ['asset_code' => 'A0004','location_id' => '2','asset_category_id' => '1',
            'name' => 'D','description' => '','installed_date' => '2020-10-10',
                ],

            ['asset_code' => 'A0005','location_id' => '2','asset_category_id' => '2',
            'name' => 'E','description' => '','installed_date' => '2020-10-10',
                ],
            ['asset_code' => 'A0006','location_id' => '2','asset_category_id' => '3',
            'name' => 'F','description' => '','installed_date' => '2020-10-10',
                ]
        ]);
    }
}
