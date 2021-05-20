<?php

namespace App\Http\Middleware;
/*Models*/
use App\Models\ProjectSettingsModel;
/*Uses*/
use Auth;
use Closure;
use Session;

class VendorMiddleware
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
        $vendor = Auth::guard('vendor')->user();
        $user = Auth::guard('user')->user();
        if($user!=null || $vendor!=null) {}
        else {
            Session::flash('error', 'Please login as Vendor first to access the page!');
            return redirect(route('frontHome'));
        }

        return $next($request);
    }
}
