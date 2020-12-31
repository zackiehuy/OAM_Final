<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AssetCategorySeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        DB::table('asset_category')->insert([
            ['name' => 'PC','category_code' => 'AC0001' ],
            ['name' => 'Laptop','category_code' => 'AC0002' ],
            ['name' => 'Monitor','category_code' => 'AC0003' ]
        ]);
    }
}
