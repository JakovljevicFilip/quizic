<?php

namespace Quizic\Rules;

use Illuminate\Contracts\Validation\Rule;
use Quizic\Game;

class QuestionIsBeingUsedRule implements Rule
{
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
        // QUESTION IS BEING USED IN A GAME
        return Game::where('question_id', $value)->first() === null;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'This question is currently used in a game!';
    }
}
