<?php

namespace App\Http\Controllers\Api;

// MODELS
use App\User;

// CLASSES
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

// REQUEST
use App\Http\Requests\UserRegistrationRequest;

class AuthController extends Controller
{
    public function register(UserRegistrationRequest $request)
    {
        // ATTEMPT TO REGISTER A NEW USER
	    User::create($request->only('username','email','password'));
		// IF SUCCESSFUL RETURN RESPONSE
		return response()->json(['messages'=>'Registration successful.'],200);
	}

	public function login(Request $request){
		// DATA NECESSARY FOR LOGIN
		$credentials = $request->only('username','password');
		// ATTEMPTS LOGIN
		if($token = auth()->attempt($credentials)){
			// LOGIN SUCCESSFUL
			return response()->json([
				// 'status'=>true,
				'messages'=>'Logged in.',
			],200)->header('Authorization',$token);
		}
		// LOGIN FAILED
		return response()->json([
			'status'=>false,
			'messages'=>'Username and/or password are wrong.',
		], 401);
	}

	public function logout(){
		$this->guard()->logout();
		return response()->json([
            'status'=>true,
            'messages'=>'Logged out.'
		],200);
	}

	// FETCHES USER INFORMATION
	public function user(Request $request){
		$user = User::find(Auth::user()->id);
		return response()->json([
			'messages' => 'User found.',
			'data' => $user
        ],200);
	}

    // VERIFIES TOKEN ON PAGE REFRESH
    // EXTENDS EXSISTING TOKEN
	public function refresh(){
        // REFRESH TOKEN
        // IF FAILED THROWS AN EXCEPTION HANDLED FROM Handler.php
        $newToken = auth()->refresh();
        // SEND NEW TOKEN
        return response()->json([
            'messages'=>'Token extended.',
        ],200)->header('Authorization',$newToken);
	}

	// SHORTHAND
	private function guard(){
		return Auth::guard();
	}

}
