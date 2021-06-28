<?php

namespace App\Http\Middleware;
/*Models*/
use App\Models\ProjectSettingsModel;
/*Uses*/
use Auth;
use Closure;
use Session;

class WebMiddleware
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
        $vendor = Auth::guard('vendor')->user();
        if($user!=null || $vendor!=null) {}
        else {
            Session::flash('error', trans('errors.login_required'));
            return redirect(route('frontLogin'));
        }

        return $next($request);
    }
}
