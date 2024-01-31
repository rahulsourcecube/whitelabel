<?php

namespace App\Http\Middleware;

use App\Models\CompanyModel;
use Closure;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        $domain = [];
        $domain = explode('.', $host);
        $CompanyModel = new CompanyModel();

        if ($domain['0'] != env('pr_name')) {
            $exitDomain = $CompanyModel->checkDmain($domain['0']);
        }

        if (!empty($exitDomain) && !empty(auth()->user()) && auth()->user()->user_type == 4) {
            $isUserValidLoginOrNot = $CompanyModel->checkUserLogin(auth()->user()->id, $exitDomain->user_id);
            if(!$isUserValidLoginOrNot){
                Session::flush();
                Auth::logout();
                return redirect()->route('user.login');
            }
        }
        if (!empty($exitDomain) && !empty(auth()->user()) && auth()->user()->user_type == 2 || auth()->user()->user_type == 3) {

            $isUserValidLoginOrNot = $CompanyModel->checkUserLogin(auth()->user()->id, $exitDomain->user_id);
            if(!$isUserValidLoginOrNot){
                Session::flush();
                Auth::logout();
                return redirect()->route('company.login');
            }
        }
        if ($domain[0] == env('pr_name') &&
            (env('ASSET_URL') . '/company/signup' || $request->url() == env('ASSET_URL') . '/signup-store'
            || request()->segment(1) == 'company' ||
            request()->segment(1) == 'admin' ||
            request()->segment(1) == 'login')) {
            return $next($request);
        } elseif ($domain['0'] != env('pr_name')  && !empty($exitDomain)) {
            return $next($request);
        } elseif (!empty(request()->segment(1)) && request()->segment(1) == 'user' && $domain['0'] != env('pr_name')) {
            return redirect()->route('error');
        } elseif ((!empty($domain['0'])) || !empty(request()->segment(1)) && (request()->segment(1) == 'company')) {
            return redirect(env('ASSET_URL') . '/company/signup');
        } else {
            return redirect('login')->with('error', "You don't have admin access.");
        }
    }
}
