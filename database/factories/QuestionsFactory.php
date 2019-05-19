<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Question;
use App\Difficulty;
use Faker\Generator as Faker;

$factory->define(Question::class, function (Faker $faker) {
    return [
        'text' => $faker->sentence(),
        'difficulty_id' => Difficulty::all()->random()->id,
        'created_at' => $faker->dateTimeThisYear($max = 'now', $timezone = null),
        'updated_at' => $faker->dateTimeThisYear($max = 'now', $timezone = null)
    ];
});
