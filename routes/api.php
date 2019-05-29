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
    Route::resource('difficulties','DifficultyController');
    Route::get('users','UserController@index');
    Route::patch('users','UserController@update');
});

// AUTHENTFICATION
Route::prefix('auth')->namespace('Api')->group(function(){
	Route::post('register','AuthController@register');
	Route::post('login','AuthController@login');
	Route::get('refresh','AuthController@refresh');

	Route::group(['middleware'=>'auth:api'], function(){
		Route::get('user','AuthController@user');
		Route::post('logout','AuthController@logout');
	});
});

// ADMIN
Route::group(['middleware'=>'isAdmin','namespace'=>'Api'],function(){
    // QUESTIONS
    Route::get('questions','QuestionController@index');
    Route::post('questions','QuestionController@store');
    Route::put('questions','QuestionController@update');
    Route::delete('questions/{id}','QuestionController@destroy');

    // USERS
	// lISTING ALL USERS INFORMATIONS, SHOULD BE ACCESSABLE BY ADMIN
	// Route::get('users','UserController@index');
	// // LISTING USER INFROMATION, SHOULD BE DONE BY EITHER ADMIN OR THE USER THEMSELF
	// Route::get('users/{id}','UserController@show')->middleware('isAdminOrSelf');
});
