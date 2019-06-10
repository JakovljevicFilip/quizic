<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckIsAdminOrSelf
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

        // USER ID THAT COMES FROM THE FRONT-END
        $id = Auth::user()->id;
        // IF USER IS AN ADMINISTRATOR OR IS TRYING TO ACCESS THEIR OWN INFORMATIONS
        if(Auth::user()->role===2 || Auth::user()->id===$id){
            // LET THEM CONTINUE
            return $next($request);
        }
        // USER DOESN'T HAVE ACCESS TO THE INFORMATION
        return response()->json([
            'message' => 'Unauthorized, You have to be either user or administrator to have access this functionality.',
            'write' => true,
        ],403);
    }
}
