<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
        	[
                'username' => 'Administrator',
                'password' => bcrypt('111111'),
                'email' => 'admin@test.com',
                'role' => 2,
                'email_verified_at' => Carbon::now(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'remember_token' => Str::random(10),
            ],
        	[
                'username' => 'JohnDoe',
                'password' => bcrypt('111111'),
                'email' => 'JohnDoe@test.com',
                'role' => 1,
                'email_verified_at' => Carbon::now(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'remember_token' => Str::random(10),
            ],
        ]);
    }
}
