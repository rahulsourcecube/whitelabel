<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckSessionTimeout
{
    public function handle($request, Closure $next)
    {
        // Check if the user is authenticated
        if (Auth::check()) {
            // Check if the session has expired
           
           
            if (!empty(session('last_activity')) && time() - session('last_activity') > config('session.lifetime') * 60) {                
                // Logout the user based on user type and redirect accordingly
               
                switch (auth()->user()->user_type) {
                    case 1:
                        Auth::logout(); 
                        return redirect()->route('login')->with('error', 'Please login again.');
                    case 2:
                        Auth::logout();
                        return redirect()->route('company.signin');
                    case 3:
                        Auth::logout(); 
                        return redirect()->route('company.signin')->with('error', 'Please login again.');
                    case 4:
                       
                        Auth::logout(); 
                        return redirect()->route('login')->with('error', 'Please login again.');
                    default:
                        Auth::logout(); 
                        return redirect()->route('login')->with('error', 'Please login again.');
                }
            }

            // Update last activity time
            session(['last_activity' => time()]);
        }

        return $next($request);
    }
}