<?php

namespace App\Http\Controllers\Api;

use App\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserUpdateRequest;

class UserController extends Controller
{
    public function index(){
        $users = User::all();
    	return response()->json([
    		'status '=> true,
    		'messages' => 'Users fetched.',
    		'users' => $users,
    	]);
    }

    public function update(UserUpdateRequest $users){
        dd('inside of model');
    //    validateAdministrators($users);
    }
}
