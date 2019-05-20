<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DifficultiesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('difficulties')->insert([
        	[
                'text'=>'Easy',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
        	[
                'text'=>'Moderate',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
        	[
                'text'=>'Hard',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]
        ]);
    }
}
