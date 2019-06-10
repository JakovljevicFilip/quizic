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

    // USER
    Route::group(['middleware'=>'isAdminOrSelf'],function(){
        // USERS
        Route::patch('users/password','UserController@changePassword');
    });

    // ADMIN
    Route::group(['middleware'=>'isAdmin'],function(){
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



