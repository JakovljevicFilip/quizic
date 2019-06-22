<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Session;

use App\Question;
use App\Answer;
use App\Hint;

class Game extends Model
{

    public function hints()
    {
        return $this->belongsToMany('App\Hint');
    }


    // STORES GAME INFORMATIONS
    private $game_id;
    // GAME INFORMATIONS
    private $game;
    // TEMPORARILY STORES QUESTION
    private $question;
    // QUESTION FOR FRONT-END
    private $questionFront;
    // USED WHEN ANSWER IS INCORRECT
    private $correctAnswerId;
    // API RESPONSE MESSAGE
    private $message;


    public function startGame(){
        // SET GAME INFO
        $this->setGameInfo();
        // CONTINUE GAME SETUP
        return $this->continueGame();
    }

    private function setGameInfo(){
        // MAKE GAME ID
        $this->game_id = Str::random();
        // MAKE NEW GAME OBJECT
        $this->game = new Game();

        // SET HASH
        $this->game->hash = $this->game_id;
        // SET SCORE
        $this->game->score = -1;
        // SET USERNAME
        $this->game->username = $this->startAs();
        // MAKE PAST QUESTIONS ARRAY
        $this->game->questions_passed = serialize([]);
        // SET MESSAGE
        $this->message = 'Game started.';
    }

    private function continueGame(){
        // INCREMENT SCORE
        $this->incrementScore();
        // GET QUESTION
        $this->getQuestion();
        // SET QUESTION VALUES IN GAME ARRAY
        $this->prepareQuestionOutput();
        // CHANGE ORDER OF ANSWERS
        $this->shuffleAnswers();
        // STORE INTO SESSION
        $this->saveGame();
        // FRONT-END OUTPUT
        return $this->outputStart();
    }

    private function incrementScore(){
        // INCREMENT SCORE
        $this->game->score++;
    }

    private function getQuestion(){
        // QUESTION DIFFICULTY
        $difficulty_id = $this->getDifficulty();
        // QUESTIONS THAT HAVE ALREADY BEEN ANSWERED
        $questions_passed = unserialize($this->game->questions_passed);

        // GET QUESTION OF CERTAIN DIFFICULTY
        $question = Question::where('difficulty_id', $difficulty_id)
        // THAT WASN'T ALREADY ANSWERED
        ->whereNotIn('id', $questions_passed)
        // WITH ANSWERS
        ->with('answers')
        // RANDOMISE ORDER OF QUESTIONS
        ->inRandomOrder()
        // GET FIRST OUT OF ARRAY OF QUESTIONS
        ->first();

        // TEMPORARILY STORE QUESTION
        $this->question = $question;
        // SET CURRENT QUESTION
        $this->game->question_id = $question->id;
        // ADD QUESTION TO THE PAST QUESTIONS ARRAY
        $questions_passed[] = $this->question->id;
        // CONVERT ARRAY TO STRING AND SET PASSED QUESTIONS
        $this->game->questions_passed = serialize($questions_passed);
    }

    private function getDifficulty(){
        // CURRENT GAME SCORE
        $score = $this->game->score;

        if($score >= 0 && $score < 5){
            // EASY
            return 1;
        }
        else if($score >=5 && $score <= 10){
            // MODERATE
            return 2;
        }
        // HARD
        return 3;
    }

    private function startAs(){
        // USER IS NOT LOGGED IN
        if(Auth::user() === null){
            return $this->startAsGuest();
        }
        // USER IS LOGGED IN
        else{
            return $this->startAsUser();
        }
    }

    private function startAsGuest(){
        // GENERATE USERNAME
        return 'guest'.mt_rand(1000, 9999);
    }

    private function startAsUser(){
        // SET USERNAME
        return Auth::user()->username;
    }

    private function prepareQuestionOutput(){
        // SET QUESTION TEXT
        $this->questionFront['text'] = $this->question->text;
        // SET QUESTION ANSWERS
        $this->questionFront['answers'] = [];

        // USED FOR LOOP ITTERATION
        $numberOfAnswers = count($this->question->answers);
        // LOOPS THROUGH ALL ANSWERS
        for($i = 0; $i < $numberOfAnswers; $i++){
            // SET ANSWER TEXT
            $this->questionFront['answers'][$i]['text'] = $this->question->answers[$i]->text;
            // SET ANSWER ID
            $this->questionFront['answers'][$i]['id'] = $this->question->answers[$i]->id;
        }
    }

    private function shuffleAnswers(){
        // MAKE COLLECTION
        $collection = collect($this->questionFront['answers']);
        // SHUFFLE ANSWERS
        $this->questionFront['answers'] = $collection->shuffle();
    }

    private function saveGame(){
        $this->game->save();
    }

    private function outputStart(){
        // OUTPUT FOR FRONT-END
        return [
            'message' => $this->message,
            'write' => false,
            'game' => [
                'game_id' => $this->game_id,
                'username' => $this->game['username'],
                'score' => $this->game['score'],
                'question' => $this->questionFront,
            ],
        ];
    }

    public function validateAnswer($answer_id){
        // ANSWER IS INCORRECT
        if(! $this->checkIfAnswerIsCorrect($answer_id)){
            // GAME ENDS
            $this->endGame();
            // RESPONSE
            return [
                'message' => 'Answer is incorrect.',
                'write' => false,
                'game' => [
                    'correct_answer' => $this->correctAnswerId,
                ],
            ];
        }
        else{
            $this->game = $this;
            $this->game->game_id = $this->hash;
            $this->message = 'Answer is correct.';
        }
        // CONTINUE GAME
        return $this->continueGame();
    }

    private function checkIfAnswerIsCorrect($answer_id){
        // GET ID OF CORRECT ANSWER
        $this->correctAnswerId = Answer::where('question_id', $this->question_id)->where('status', 1)->first()->id;
        // ANSWER IS CORRECT
        if($this->correctAnswerId == intval($answer_id)){
            return true;
        }
        // ANSWER IS INCORRECT
        return false;
    }

    private function endGame(){
        $this->delete();
    }

    public function half(){
        // SET HINT AS USED
        $this->setHintAsUsed(2);
        // GET TWO INCORRECT ANSWERS
        return $this->getTwoIncorrectAnswers();
    }

    private function setHintAsUsed($hint_id){
        // FIND HINT
        $hint = Hint::find($hint_id);
        // SET HINT AS USED
        $this->hints()->save($hint);
    }

    private function getTwoIncorrectAnswers(){
        // GET IDS
        return Answer::select('id')
            // FOR CURRENT QUESTION
            ->where('question_id', $this->question_id)
            // THAT ARE INCORRECT
            ->where('status', 0)
            // SHUFFLE RESULTS
            ->inRandomOrder()
            // LIMIT NUMBER OF RESULTS TO 2
            ->limit(2)
            // GET
            ->get()
            // CONVERT RESULTS TO ARRAY
            ->toArray();
    }

    public function solve(){
        dd(1);
    }
}
