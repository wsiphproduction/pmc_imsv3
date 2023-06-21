<?php

namespace App\Http\Middleware;

use App\users;
use Closure;
use Illuminate\Support\Facades\Auth;

class AuthenticatedMiddleware
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
        if(Auth::check()){
            $isLogggedIn = Auth::user()->isLoggedIn;
            if($isLogggedIn == null){
                $isLogggedIn = 1;
            }
            if($isLogggedIn == 0)
            {
                Auth::logout();
               abort(404); 
            }
            return $next($request);

        } abort(404);
    }
    
}
