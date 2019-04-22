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
                'text'=>'easy',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
        	[
                'text'=>'moderate',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
        	[
                'text'=>'hard',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]
        ]);
    }
}
