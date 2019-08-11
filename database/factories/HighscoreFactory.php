<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use Quizic\Highscore;
use Faker\Generator as Faker;

$factory->define(Highscore::class, function (Faker $faker) {
    return [
    	'username'=>$faker->userName,
    	'score' => rand(1,5),
        'created_at' => $faker->dateTimeThisYear($max = 'now', $timezone = null)
    ];
});
