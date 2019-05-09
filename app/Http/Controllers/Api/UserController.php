<?php

namespace App\Http\Controllers\Api;

use App\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    public function index(){
    	$users=User::all();
    	return response()->json([
    		'status'=>true,
    		'messages'=>'Users fetched.',
    		'users'=>$users,
    	]);
    }

    public function show(User $user){
    	$user=$user->all();
    	return response()->json([
    		'status'=>true,
    		'messages'=>'User fetched',
    		'user'=>$user->all(),
    	]);
    }
}
