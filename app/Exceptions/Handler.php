<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
// NOT FOUND
use Illuminate\Database\Eloquent\ModelNotFoundException;
// VALIDATION
use Illuminate\Validation\ValidationException;
// JWT
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenBlacklistedException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;



class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        // VALDIATION EXCEPTIONS
        if($exception instanceof ValidationException){
            // BREAK DOWN OBJECT INTO ARRAY OF STRINGS
            $message = collect($exception->errors())->collapse();
            // 409 - CONFLICT
            return response()->json([
                'message' => $message,
                'write' => true,
            ], 422);
        }
        // JWT TOKEN EXCEPTIONS
        if ($exception instanceof JWTException) {
            // EXPIRED TOKEN
            // 401 - UNAUTHORIZED
            if ($exception instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException) {
                return response()->json([
                    'message' => 'Token expired.',
                    'write' => true,
                ], 401);
            }
            // INVALID TOKEN
            else if ($exception instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException) {
                return response()->json([
                    'message' => 'Token invalid.',
                    'write' => false,
                ], 401);
            }
            // BLAKLISTED TOKEN
            else if ($exception instanceof \Tymon\JWTAuth\Exceptions\TokenBlacklistedException) {
                return response()->json([
                    'message' => 'Token blacklisted.',
                    'write' => false,
                ], 401);
            }
            // TOKEN COULD NOT BE PARSED
            else if ($exception->getMessage() === 'Token could not be parsed from the request.') {
                return response()->json([
                    'message' => 'Token could not be parsed.',
                    'write' => false,
                ], 401);
            }
            // THIS ONE IS OKAY SINCE IT MEANS THAT USER HAS NOT LOGGED IN YET
            else if ($exception->getMessage() === 'Token not provided') {
                return response()->json([
                    'message' => 'Token not provided.',
                    'write' => false,
                ], 200);
            }
            // OTHER ERRORS
            else{
                return response()->json('JWT error: '.$exception->getMessage(), 500);
            }
        }
        // NOT FOUND
        if ($exception instanceof ModelNotFoundException) {
            return response()->json([
                'message' => 'Resource not Found!',
                'write' => true,
            ], 404);
        }
        return parent::render($request, $exception);
    }
}
