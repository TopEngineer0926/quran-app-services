<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Enum;
use Carbon\Carbon;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
        //////////////////////////////////////////Start Navigation////////////////////////////////////////////////////////
        View::composer('*', function ($view) {
            $nav[0] = [
                'name' => 'Dashboard',
                'icon' => 'clip-home',
                'link' => route('admin.dashboard'),
            ];
            $nav[1] = [
                'name' => 'Verse By Verse',
                'icon' => 'fa fa-upload',
                'link' => route('vbv.index'),
            ];
            $nav[2] = [
                'name' => 'Word By Word',
                'icon' => 'fa fa-upload',
                'link' => route('wbw.index'),
            ];

            $view->with('navigation', $nav);
        });
        //////////////////////////////////////////End Navigation///////////////////////////////////////////////////////
    }
}
