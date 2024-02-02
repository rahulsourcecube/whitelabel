<?php

namespace App\Http\Middleware;

use App\Helpers\Helper;
use App\Models\CompanyPackage;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BuyPackage
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
        $user = Auth::user();
       
        $companyId = Helper::getCompanyId();
        if ($user->user_type == '2') {
            $checkPackage = CompanyPackage::where('company_id', $companyId)->exists();
           
            if (!empty($checkPackage)) {
                return $next($request);
            }else{
                return redirect()->route('company.package.list', 'Free')->withErrors('Please buy package to access this page');
            }
        } else {
            return $next($request);
        }
        return redirect()->route('company.package.list', 'Free')->withErrors('Please buy package to access this page');
    }
}
