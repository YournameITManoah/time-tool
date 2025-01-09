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

        if (!$request->isMethod('POST')) return $next($request);

        $contentType = $request->header('Content-Type');
        if ($contentType != 'application/json') {
            return response()->json(['message' => 'Unsupported Media Type'], 415, ['Accept-Post' => 'application/json; charset=UTF-8']);
        }

        return $next($request);;
    }
}
