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
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
        	[
                'username' => 'User',
                'password' => bcrypt('111111'),
                'email' => 'user@test.com',
                'role' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
        ]);
    }
}
