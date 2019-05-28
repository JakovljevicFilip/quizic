<?php

namespace App\Http\Controllers\Api;

// MODELS
use App\User;

// CLASSES
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

// REQUEST
use App\Http\Requests\UserRegistrationRequest;

class AuthController extends Controller
{
    public function register(UserRegistrationRequest $request)
    {
        // ATTEMPT TO REGISTER A NEW USER
        User::create($request->only('username','email','password'));
		// IF SUCCESSFUL RETURN RESPONSE
		return response()->json([
            'title' => 'Registration',
            'message' => 'Registration successful.',
            'write' => true,
        ],200);
	}

	public function login(Request $request){
		// GET DATA NECESSARY FOR LOGIN
		$credentials = $request->only('username','password');
		// ATTEMPT LOGIN
		if($token = auth()->attempt($credentials)){
			// LOGIN SUCCESSFUL
			return response()->json([
                'title' => 'Login',
                'message' => 'Logged in.',
                'write' => true,
			],200)->header('Authorization',$token);
		}
		// LOGIN FAILED
		return response()->json([
            'title' => 'Login',
            'message' => 'Username and/or password are wrong.',
            'write' => true,
		], 401);
	}

	public function logout(){
		$this->guard()->logout();
		return response()->json([
            'title' => 'Logout',
            'message' => 'Logged out.',
            'write' => true,
		],200);
	}

	// FETCHES USER INFORMATION
	public function user(Request $request){
		$user = User::find(Auth::user()->id);
		return response()->json([
            'title' => 'User',
            'message' => 'User found.',
            'write' => false,
			'data' => $user,
        ],200);
	}

    // VERIFIES TOKEN ON PAGE REFRESH
    // EXTENDS EXSISTING TOKEN
	public function refresh(){
        // REFRESH TOKEN
        $newToken = auth()->refresh();
        // SEND NEW TOKEN
        return response()->json([
            'title' => 'Token',
            'message' => 'Token extended.',
            'write' => false,
        ],200)->header('Authorization',$newToken);
	}

	// SHORTHAND
	private function guard(){
		return Auth::guard();
	}

}
