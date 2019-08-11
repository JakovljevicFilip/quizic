<?php

namespace Quizic\Rules;

use Illuminate\Contracts\Validation\Rule;
use Quizic\Game;
use Quizic\Hint;

class HintRule implements Rule
{
    // MESSAGE IN CASE VALIDATION FAILED
    private $message;
    // REFERENCE TO THE CURRENT GAME
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
        // ATTEMPT TO GET GAME
        // HASH IS INVALID
        if(! $this->game = $this->getGame($value['hash'])){
            // SET ERROR MESSAGE
            $this->message = 'Inavlid game hash, please restart the game.';
            // VALDIATION FAILS
            return false;
        }

        // ATTEMPT TO GET HINT
        if(! $this->hint = $this->getHint($value['text'])){
            // SET ERROR MESSAGE
            $this->message = 'Text parameter hasn\'t been sent properly.';
            // VALDIATION FAILS
            return false;
        }
        // HINT HAS BEEN USED
        if($this->hasHintBeenUsed()){
            // SET ERROR MESSAGE
            $this->message = 'Hint has already been used on this game.';
            // VALDIATION FAILS
            return false;
        }
        // VALDIATION PASSES
        return true;
    }

    private function getGame($hash){
        // DOES GAME WITH THIS HASH EXISTS
        return Game::where('hash', $hash)->first();
    }

    private function getHint($text){
        // DOES GAME WITH THIS CERATIN TEXT EXISTS
        return Hint::where('text', $text)->first();
    }

    private function hasHintBeenUsed(){
        // DOES GAME WITH THIS CERATIN HASH EXISTS
        return $this->game->hints->contains($this->hint->id);
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
