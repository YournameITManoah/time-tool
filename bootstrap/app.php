<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Middleware;
use Hybridly\Exceptions\HandleHybridExceptions;
use Symfony\Component\HttpFoundation\Response;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->statefulApi();
        $middleware->prependToGroup('api', [\App\Http\Middleware\Localization::class, \App\Http\Middleware\ForceJsonResponse::class]);
        $middleware->appendToGroup('web', [\App\Http\Middleware\Localization::class, \App\Http\Middleware\HandleHybridRequests::class]);
    })
    ->withExceptions(
        HandleHybridExceptions::register()
            ->inEnvironments('local')
            ->renderUsing(fn(Response $response) => hybridly('error', [
                'status' => $response->getStatusCode(),
            ]))
            ->expireSessionUsing(fn() => back()->with([
                'error' => 'Your session has expired. Please refresh the page.',
            ]))
    )->create();
