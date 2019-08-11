<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Quizic\Question;

class AnswersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // GET ALL QUESTION IDS
        $questions = Question::all()->pluck('id');
        // LOOP THROUGH QUESTION IDS
        $questions->each(function($item){
            // CORRECT ANSWER INDEX
            $correctAnswer = rand(1,4);
            // ANSWERS ARRAY
            $answers = [];

            // LOOP 4 TIMES, THE NUMBER OF ANSWERS
            for($i = 1; $i <= 4; $i++){
                // INDEX OF CORRECT ANSWER
                if($i === $correctAnswer){
                    // APPEND CORRECT ANSWER TO ANSWERS ARRAY
                    $answers[] = [
                        'text'=>'Correct answer',
                        'status'=>true,
                        'question_id'=>$item
                    ];
                }
                // OTHER INDEX
                else{
                    // APPEND INCORRECT ANSWER TO ANSWERS ARRAY
                    $answers[] = [
                        'text'=>'Incorrect answer',
                        'status'=>false,
                        'question_id'=>$item
                    ];
                }
            }

            // INSERT ANSWERS TO DB
            DB::table('answers')->insert($answers);
        });
    }
}
