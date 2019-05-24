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
        $questions = Question::all()->pluck('id');
        $questions->each(function($item){
            DB::table('answers')->insert([
                [
        			'text'=>'Correct answer',
        			'status'=>true,
        			'question_id'=>$item
        		],
        		[
        			'text'=>'Incorrect answer',
        			'status'=>false,
        			'question_id'=>$item
        		],
        		[
        			'text'=>'Incorrect answer',
        			'status'=>false,
        			'question_id'=>$item
        		],
        		[
        			'text'=>'Incorrect answer',
        			'status'=>false,
        			'question_id'=>$item
        		],
        	]);
        });
    }
}
