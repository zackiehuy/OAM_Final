<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class SpecificationSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        DB::table('specification')->insert([
            ['name' => 'RAM','specification_code' => 'S0001' , 'is_required' => true],
            ['name' => 'CPU','specification_code' => 'S0002' , 'is_required' => true],
            ['name' => 'HDD','specification_code' => 'S0003' , 'is_required' => true],
            ['name' => 'Display','specification_code' => 'S0004' , 'is_required' => true],
            ['name' => 'Screen size','specification_code' => 'S0005' , 'is_required' => true],
            ['name' => 'Bright','specification_code' => 'S0006' , 'is_required' => true]
        ]);
    }
}
