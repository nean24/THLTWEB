<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    protected $middlewareGroups = [
        'web' => [
            // ...existing code...
            \App\Http\Middleware\EnsureSupabaseSession::class,
        ],

        'api' => [
            // ...existing code...
        ],
    ];

    // ...existing code...
}