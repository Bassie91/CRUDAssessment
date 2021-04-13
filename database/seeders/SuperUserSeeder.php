<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class SuperUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('super_users')->insert([
            'name' => 'app',
            'email' => 'api@app.com',
            'password' => bcrypt('test1234'),
        ]);
    }
}
