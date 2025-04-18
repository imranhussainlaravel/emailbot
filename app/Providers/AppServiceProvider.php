<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Cache;
use App\Models\Categories;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        View::composer('layout.header', function ($view) {
            // Cache the navbar data to reduce database queries
            $navbarData = Cache::remember('navbar_data', 60, function () {
                return Categories::where('status', 'active')->get();
            });
            

            $nav1 = [];
            $nav2 = [];
            $nav3 = [];

            foreach ($navbarData as $category) {
                switch ($category->nav_id) {
                    case 1:
                        $nav1[] = $category;
                        break;
                    case 2:
                        $nav2[] = $category;
                        break;
                    case 3:
                        $nav3[] = $category;
                        break;
                    default:
                        // Handle other nav_ids or skip if not needed
                        break;
                }
            }

            // Share the navbar data with the header view
            $view->with([
                'nav1' => $nav1,
                'nav2' => $nav2,
                'nav3' => $nav3
            ]);
        });
    }
}
