<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Company
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
        // && (auth()->user()->user_type == '2' || auth()->user()->user_type == '3')
         if(auth()->user() ){
            return $next($request);
        }
        // dd(auth()->user());
        return redirect('login')->with('error',"You don't have admin access.");
    }
}
