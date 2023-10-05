<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureTokenIsValid
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        # Get Token from Request Uri
        $apiToken = $request->input('api_token');
        /****************************************************
         * logic here to handle the request with $apiToken.
         * Goes Here
         * If Token Valid Proceed to Controller
         * else return error from here 
         * ***************************************************/
        return $next($request);
    }
}
