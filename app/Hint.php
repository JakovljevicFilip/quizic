<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Hint extends Model
{
    public $timestamps = false;

    public function games()
    {
        return $this->belongsToMany('App\Game');
    }

    // REFERENCE TO THE GAME
    private $game;
    private $hintText;
    private $hintResponse;

    public function useHint($game, $hintText){
        // SET GAME REFERENCE
        $this->game = $game;
        // SET HINT TEXT REFERENCE
        $this->hintText = $hintText;
        // RUN HINT METHOD
        $this->$hintText();
        // SET HINT AS USED
        $this->setHintAsUsed();
        // RETURN HINT RESPONSE
        return $this->response();
    }

    private function half(){
        // GET TWO INCORRECT ANSWERS
        $this->hintResponse = $this->getTwoIncorrectAnswers();
    }

    private function getTwoIncorrectAnswers(){
        // GET IDS
        return Answer::select('id')
            // FOR CURRENT QUESTION
            ->where('question_id', $this->game->question_id)
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

    private function setHintAsUsed(){
        // SET HINT AS USED
        $this->games()->save($this->game);
    }

    private function response(){
        // HINT RESPONSE
        return [
            'message' => 'Hint '.$this->hintText.' has been used.',
            'write' => false,
            'hint' => $this->hintResponse,
        ];
    }

    private function solve(){
        $this->hintResponse = $this->getTheCorrectAnswer();
    }

    private function getTheCorrectAnswer(){
        // FIND THE CORRECT ANSWER FOR THE QUESTION
        return Answer::where('question_id', $this->game->question_id)->where('status', 1)->first();
    }

    private function switch(){
        // CONTINUE GAME
        $this->hintResponse = $this->game->switch();
    }
}
