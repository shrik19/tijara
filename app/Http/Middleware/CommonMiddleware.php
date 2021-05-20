<?php

namespace App\Http\Middleware;
/*Models*/
use App\Models\ProjectSettingsModel;
/*Uses*/
use Auth;
use Closure;
use Session;

class CommonMiddleware
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
        $user = Auth::guard('user')->user();
        if($user!=null) {}
        else {
            $user = Auth::guard('vendor')->user();
            if($user!=null) {}
            else {
                Session::flash('error', 'Please login first to access the pages!');
                return redirect(route('frontHome'));
            }
        }

        return $next($request);
    }
}
