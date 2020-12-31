<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class CategorySpecificationSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        //category_specification
        DB::table('category_specification')->insert([
            ['asset_category_id' => '1','specification_id' => '1' , 'default_value' => '8GB DDR4 1x8G 2666'],
            ['asset_category_id' => '1','specification_id' => '2' ,
                'default_value' => 'CPU Intel Core i5-9400F
                (2.9GHz turbo up to 4.1GHz, 9MB Cache, 65W) -Socket Intel LGA 1151-v2'],
            ['asset_category_id' => '1','specification_id' => '3' ,
                'default_value' => 'HDD WD Blue 3TB 3.5 inch SATA III 64MB Cache 5400RPM WD30EZRZ'],
            ['asset_category_id' => '2','specification_id' => '4' ,
                'default_value' => 'Laptop Apple Macbook Air MJVM2ZP/A i5 5250U/4GB/128GB'],
            ['asset_category_id' => '2','specification_id' => '1' ,
                'default_value' => 'Ram Laptop Kingston DDR4 4GB Bus 2666 KVR26S19S6/4'],
            ['asset_category_id' => '2','specification_id' => '2' , 'default_value' => 'CPU INTEL Core I5 3470 3.2Ghz'],
            ['asset_category_id' => '3','specification_id' => '5' , 'default_value' => '16"-18.9"'],
            ['asset_category_id' => '3','specification_id' => '6' ,
                'default_value' => 'FW279 ULTRA BRIGHT 7IN 2200NIT 4K']
        ]);
    }
}
