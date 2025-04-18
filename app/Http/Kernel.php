<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array
     */
    protected $middleware = [
        // Middleware that applies globally
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array
     */
    protected $middlewareGroups = [
        'web' => [
           
        ],

        'api' => [
            
        ],
    ];

    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'admin.guest' => \App\Http\Middleware\RedirectIfAdminAuthenticated::class,
        'admin.auth' => \App\Http\Middleware\AdminAuthenticate::class, // You'll need to create this one!
    ];
    
    
}
