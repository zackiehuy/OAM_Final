<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AssetDetailSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        DB::table('asset_detail')->insert([
            ['asset_id' => '1','specification_id' => '1',
            'value' => '8GB DDR4 1x8G 2666'],
            ['asset_id' => '1','specification_id' => '2',
            'value' => 'CPU Intel Core i5-9400F
            (2.9GHz turbo up to 4.1GHz, 9MB Cache, 65W) - Socket Intel LGA 1151-v2'],
            ['asset_id' => '1','specification_id' => '3',
            'value' => 'HDD WD Blue 3TB 3.5 inch SATA III 64MB Cache 5400RPM WD30EZRZ'],
        ]);
    }
}
