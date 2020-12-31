<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AssignmentHistorySeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        DB::table('assignment_history')->insert([
            ['assignment_id' => '1','changed_date' => '2020-10-10',
            'status_id' => '2']
        ]);
    }
}
