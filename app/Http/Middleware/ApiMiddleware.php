<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class ApiMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $request->headers->set('Accept', 'application/json');
        $request->headers->set('Origin', $request->header('Origin', $request->getHttpHost()));

        $contentType = $request->header('Content-Type');
        if ($request->isMethod('POST') && $contentType != 'application/json') {
            abort(415, 'Unsupported Media Type.', ['Accept-Post' => 'application/json; charset=UTF-8']);
        }

        if (in_array($request->getMethod(), ['POST', 'PUT', 'PATCH'])) {
            json_decode($request->getContent());
            if (json_last_error() !== JSON_ERROR_NONE) {
                abort(400, 'Bad Request.');
            }
        }

        return $next($request);
        ;
    }
}
