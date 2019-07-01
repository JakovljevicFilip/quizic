<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::namespace('Api')->group(function(){
    // HIGHSCORES
    Route::get('highscores', 'HighscoreController@index');

    // AUTHENTFICATION
    Route::prefix('auth')->group(function(){

        Route::post('register','AuthController@register');
        Route::post('login','AuthController@login');
        Route::get('refresh','AuthController@refresh');

        Route::group(['middleware'=>'auth:api'], function(){
            Route::get('user','AuthController@user');
            Route::post('logout','AuthController@logout');
        });
    });

    // GAME
    Route::prefix('game')->group(function(){
        Route::get('startGame', 'GameController@startGame');
        Route::get('answer', 'GameController@answer');
        Route::delete('destroy', 'GameController@destroy');
        Route::get('hint', 'GameController@hint');
    });

    // USER
    Route::group(['middleware'=>'isAdminOrSelf'],function(){
        // USERS
        Route::patch('users/password','UserController@changePassword');
        // // HIGHSCORES
        // Route::get('highscores', 'HighscoreController@index');
    });

    // ADMIN
    Route::group(['middleware'=>'isAdmin'],function(){
        // DIFFCIULTIES
        Route::get('difficulties', 'DifficultyController@index');

        // QUESTIONS
        Route::get('questions','QuestionController@index');
        Route::post('questions','QuestionController@store');
        Route::put('questions','QuestionController@update');
        Route::delete('questions/{id}','QuestionController@destroy');

        // USERS
        Route::get('users','UserController@index');
        Route::patch('users/role','UserController@changeRole');
        Route::delete('users','UserController@destroy');
    });

});



