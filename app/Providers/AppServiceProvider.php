<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;

use DB;
use View;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    { 
        Paginator::useBootstrap();
        $siteDetails = DB::table('settings')->first();		
        View::composer('Front/layout/header', function($view) use($siteDetails) {		
            $view->with('siteDetails',$siteDetails);		});		
            View::composer('Front/layout/footer', function($view) use($siteDetails) {		
                $view->with('siteDetails',$siteDetails);		});
    }
}
