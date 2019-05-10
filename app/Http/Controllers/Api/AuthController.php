<?php

namespace App\Http\Controllers\Api;

// MODELS
use App\User;

// CLASSES
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;


class AuthController extends Controller
{
	public function register(Request $request){
		// VALIDATION
		$validation = Validator::make($request->all(),[
			'username'=>'required|string|unique:users',
			'email'=>'required|string|email|unique:users',
			'password'=>'required|min:6|confirmed',
		]);
		// DID IT FAIL?
		if($validation->fails()){
			// STOPS THE CODE
			return response()->json([
				'status'=>false,
				'messages'=>$validation->errors()->all(),
			],422);
		}
		// CREATES NEW USER
		$user=new User();
		$user->username=$request->username;
		$user->email=$request->email;
		$user->password=bcrypt($request->password);
		$user->save();

		// RETURNS RESPONSE
		return response()->json([
			'status'=>true,
			'messages'=>'Registration successful.',
		],200);
	}

	public function login(Request $request){
		// VALIDATION
		$validation = Validator::make($request->all(),[
			'username'=>'required|string|max:255',
			'password'=>'required|min:6',
		]);
		// DID IT FAIL?
		if($validation->fails()){
			// STOPS THE CODE
			return response()->json([
				'status'=>false,
				'messages'=>$validation->errors()->all(),
			],422);
		}

		// DATA NECESSARY FOR LOGIN
		$credidentials=$request->only('username','password');
		// ATTEMPTS LOGIN
		if($token=$this->guard()->attempt($credidentials)){
			// LOGIN SUCCESSFUL
			return response()->json(['status'=>true],200)->header('Authorization',$token);
		}
		// LOGIN FAILED
		return response()->json([
			'status'=>false,
			'messages'=>'Login error: invalid credidentials.',
		], 401);
	}

	public function logout(){
		$this->guard()->logout();
		return response()->json([
			'status'=>true
		],200);
	}

	// REFRESHES AUTHORIZATION TOKEN
	public function refresh(){
		if($token=$this->guard()->refresh()){
			return response()->json([
				'status'=>true
			],200);
		}

		return response()->json([
			'status'=>false
		],401);
	}

	// SHORTHAND
	private function guard(){
		return Auth::guard();
	}

}
