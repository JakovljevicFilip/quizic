<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
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
        if ($exception instanceof JWTException) {
            // EXPIRED TOKEN
            // 401 - UNAUTHORIZED
            if ($exception instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException) {
                return response()->json(['token_expired'], 401);
            }
            // INVALID TOKEN
            else if ($exception instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException) {
                return response()->json(['token_invalid'], 401);
            }
            // BLAKLISTED TOKEN
            else if ($exception instanceof \Tymon\JWTAuth\Exceptions\TokenBlacklistedException) {
                return response()->json(['token_blacklisted'], 401);
            }
            // TOKEN COULD NOT BE PARSED
            else if ($exception->getMessage() === 'Token could not be parsed from the request.') {
                return response()->json(['token_could_not_be_parsed'], 401);
            }
            // THIS ONE IS OKAY SINCE IT MEANS THAT USER HAS NOT LOGGED IN YET
            else if ($exception->getMessage() === 'Token not provided') {
                return response()->json(['token_not_provided'], 200);
            }
            // OTHER ERRORS
            else{
                return response()->json('JWT error: '.$exception->getMessage(), 500);
            }
        }
        return parent::render($request, $exception);
    }
}
