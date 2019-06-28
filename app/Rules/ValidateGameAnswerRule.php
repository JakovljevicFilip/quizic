<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Session;
use App\Game;
use App\Answer;

class ValidateGameAnswerRule implements Rule
{
    private $message;
    private $game;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        // GAME SESSION IS INVALID
        if(! $this->validateSession($value['hash'])){
            $this->message = 'Game session is invalid. Please restart the game.';
        }
        else if(! $this->validateAnswer($value['id'])){
            $this->message = 'Invalid answer to a question. Please restart the game.';
        }
        else{
            // VALIDATION FAILED
            return true;
        }
        // VALDIATION PASSED
        return false;
    }

    private function validateSession($hash){
        $result = Game::where('hash', $hash)->first();

        // THERE IS NO ROW IN DB WITH CERTAIN HASH VALUE
        if($result === null){
            // FAILED
            return false;
        }
        // THERE IS A ROW
        $this->game = $result;
        // PASSED
        return true;
    }

    private function validateAnswer($id){
        $result = Answer::where('id', $id)->where('question_id', $this->game->question_id)->get();

        // THERE IS NO ROW IN DB WITH CERTAIN HASH VALUE
        if($result->isEmpty()){
            // FAILED
            return false;
        }
        // PASSED
        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->message;
    }
}
