<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
//use Illuminate\Database\Eloquent\Model;
//use Faker\Factory as Faker;
//use Illuminate\Database\Factory;
use Illuminate\Database\Eloquent\Factories\Factory as Factory;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        //$faker = Faker::create();
        //$factory->defineAs(App\Models\Post::class, 100, function ($faker) use ($factory) {
        //    'title' => $faker->title,
        //    'body' => $faker->text,
        //});
        $this->call([
            StatusSeeder::class,
            LocationSeeder::class,
            AssetCategorySeeder::class,
            SpecificationSeeder::class,
            CategorySpecificationSeeder::class,
            AssetSeeder::class,
            AssetDetailSeeder::class,
            UserSeeder::class,
            AssignmentSeeder::class,
            AssignmentHistorySeeder::class,
            RequestSeeder::class,
        ]);
    }
}
