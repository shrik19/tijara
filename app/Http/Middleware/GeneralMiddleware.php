<?php

namespace App\Http\Middleware;

/*Models*/
use App\Models\GroupPermissionModel;
use App\Models\ModuleModel;

use Auth;
use Closure;

class GeneralMiddleware
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
        $role_permission = $modules = [];
        $user = Auth::guard('admin')->user();
        if($user!=null) {
        //     $user = $user->toArray();
        //     if($user['user_type']!='admin') {
        //         $role_permission = GroupPermissionModel::where('role_id',$user['group_id'])->get();
        //         if(count($role_permission)>0) {
        //             $role_permission = $role_permission->toArray();
        //         }
        //     }
        //     view()->share('Loggeduser', $user);
        //     view()->share('LoggeduserPermission', $role_permission);
        //     $request->attributes->add(['Loggeduser' => $user]);
        //     $request->attributes->add(['LoggeduserPermission' => $role_permission]);
            
        //     $modules = ModuleModel::where('status','active')->orderBy('order_by')->get();
        //     if(count($modules)>0) {
        //         $modules = $modules->toArray();
        //     }

        //     /* START : Check if Module Path is Active */
        //     $current_path = '/'.$request->path();
        //     $is_module_path_found = 0;
        //     foreach($modules as $module)
        //     {
                
        //         if(strpos($current_path, $module['module_link']) !== false)
        //         {
        //             $is_module_path_found++;
        //         }
        //     }

        //     if($is_module_path_found == 0)
        //     {
        //         return abort(404);
        //     }

        //     /* END : Check if Module Path is Active */

        //     view()->share('modulesArr', $modules);
        //     $request->attributes->add(['modulesArr' => $modules]);
        }
        else {
            return redirect(route('adminLogin'));
        }
        return $next($request);
    }
}
