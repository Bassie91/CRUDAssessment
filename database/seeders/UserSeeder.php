<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {   
        
        for($i = 0;$i<20;$i++){
            DB::table('users')->insert([
                'firstname' => Str::random(10),
                'surname' => Str::random(10),
                'dob' => Carbon::today()->subDays(rand(0, 365)),
                'phone' => mt_rand(100000, 999999),
                'email' => Str::random(10).'@gmail.com',
            ]);
        };
    }
}
