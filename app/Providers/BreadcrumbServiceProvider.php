<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Helpers\BreadcrumbHelper;

class BreadcrumbServiceProvider extends ServiceProvider
{
    public function boot()
    {
        View::composer('*', function ($view) {
            $view->with('breadcrumbData', BreadcrumbHelper::get());
        });
    }

    public function register()
    {
        require_once app_path('Helpers/BreadcrumbHelper.php');
    }
}