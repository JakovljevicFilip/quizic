<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Difficulty;
use Carbon\Carbon;

class QuestionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    		$difficulty=Difficulty::where('text','easy')->get()->pluck('id')[0];
    		for($i=1;$i<5;$i++){
    			DB::table('questions')->insert([
	        	'text'=>'Example question '.$i,
	        	'difficulty_id'=>$difficulty,
	        	'created_at' => Carbon::now(),
	        	'updated_at' => Carbon::now()
        	]);
    		}
    }
}