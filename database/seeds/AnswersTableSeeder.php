<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Question;

class AnswersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for($i=1;$i<5;$i++){
        	$question=Question::where('text','Example question '.$i)->get()->pluck('id')[0];
        	DB::table('answers')->insert([
        		[
        			'text'=>'Incorrect answer',
        			'status'=>false,
        			'question_id'=>$question
        		],
        		[
        			'text'=>'Incorrect answer',
        			'status'=>false,
        			'question_id'=>$question
        		],
        		[
        			'text'=>'Incorrect answer',
        			'status'=>false,
        			'question_id'=>$question
        		],
        		[
        			'text'=>'Correct answer',
        			'status'=>true,
        			'question_id'=>$question
        		]
        	]);
        }
    }
}
