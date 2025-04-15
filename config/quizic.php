<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Easy Questions Threshold
    |--------------------------------------------------------------------------
    |
    | This value defines the number of questions a player must answer before
    | progressing beyond the "easy" difficulty level. For example, if set to 5,
    | then questions 1 through 5 will be considered "easy".
    |
    */

    'easy_question_limit' => env('QUIZIC_EASY_QUESTION_LIMIT', 5),

    /*
    |--------------------------------------------------------------------------
    | Moderate Questions Threshold
    |--------------------------------------------------------------------------
    |
    | This value defines the upper boundary for "moderate" questions.
    | For example, if set to 10, then questions 6 through 10 will be
    | considered "moderate". All questions after this will be "hard".
    |
    */

    'moderate_question_limit' => env('QUIZIC_MODERATE_QUESTION_LIMIT', 10),

];
