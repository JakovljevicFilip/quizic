<?php

use Illuminate\Database\Seeder;

class HintsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('hints')->insert([
        	[
                // REMOVE 2 INCORRECT ANSWERS
                'text'=>'half',
            ],
        	[
                // GET ANOTHER QUESTION OF SAME DIFFICULTY
                'text'=>'switch',
            ],
        	[
                // SOLVE CURRENT QUESTION
                'text'=>'solve',
            ]
        ]);
    }
}
