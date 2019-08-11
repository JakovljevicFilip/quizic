<?php

namespace Quizic\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckIsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // CHECK IF USER IS LOGGED IN
        if(! is_object(Auth::user())){
            // USER IS NOT LOGGED IN
            return response()->json([
                'message' => 'Unauthorized access, you are not logged in.',
                'write' => false,
            ],403);
        }
        // CHECK IF USER IS ADMINISTRATOR
        if(Auth::user()->role===2){
            // USER IS ADMINISTRATOR
            return $next($request);
        }
        // USER IS NOT AN ADMINISTRATOR
        return response()->json([
            'message' => 'Unauthorized access, you need to be an administrator in order to have access to this functionality.',
            'write' => false,
        ],403);
    }
}
