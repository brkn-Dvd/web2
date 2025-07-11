<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )

    ->withMiddleware(function (Middleware $middleware) {
        // Registrar seu middleware de role
        $middleware->alias([
            'role' => \App\Http\Middleware\CheckRole::class,
        ]);
    })

    ->withPolicies([
        \App\Models\User::class => \App\Policies\UserPolicy::class,
        // Adicione outras policies aqui conforme necessário
    ])

    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
