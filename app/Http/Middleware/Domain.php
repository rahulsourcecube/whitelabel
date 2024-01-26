<?php

namespace App\Http\Middleware;

use App\Models\CompanyModel;
use Closure;
use Illuminate\Http\Request;

class Domain 
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
      
        $host = $request->getHost();        
        $domain= explode('.', $host);
    
        $CompanyModel = CompanyModel::checkDmain(auth()->user('id'));
        dd( $CompanyModel);
        
        // if(auth()->user() && auth()->user()->user_type == 1 ){
        //     return $next($request);
        // }
        // return redirect('login')->with('error',"You don't have admin access.");
    }
}
