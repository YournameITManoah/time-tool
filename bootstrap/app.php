<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Http\Request;
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
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->respond(function (Response $response, Throwable $exception, Request $request) {
            if (!$request->is('api*') && !$request->is('admin*') && !$request->is('docs/api*') && in_array($response->getStatusCode(), [401, 403, 404, 500, 503])) {
                $globals = new \App\Http\Middleware\HandleHybridRequests();
                return hybridly()->share($globals->share())->view('error', ['status' => $response->getStatusCode()])
                    ->toResponse($request)
                    ->setStatusCode($response->getStatusCode());
            } elseif (!$request->is('api*') && !$request->is('admin*') && !$request->is('docs/api*') && $response->getStatusCode() === 419) {
                return back()->with(['error' => __('messages.error_session_expired')]);
            }

            return $response;
        });
    })->create();
