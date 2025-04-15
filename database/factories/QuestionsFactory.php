<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use Faker\Generator as Faker;
use Quizic\Question;
use Quizic\Support\Difficulty;

$factory->define(Question::class, function (Faker $faker) {
    return [
        'text' => $faker->sentence(),
        'difficulty_id' => Difficulty::getRandomDifficulty(),
        'created_at' => $faker->dateTimeThisYear($max = 'now', $timezone = null),
        'updated_at' => $faker->dateTimeThisYear($max = 'now', $timezone = null)
    ];
});
