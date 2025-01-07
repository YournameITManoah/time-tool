<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\Response;

class Localization
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // If session locale, use that
        if (Session::has('locale')) {
            App::setLocale(Session::get('locale'));
        } else {
            $supported = array_keys(\Config::get('app.locales'));
            $preferred = $this->parseAcceptLanguage($request);

            // Use the first supported locale of preferred locales
            foreach ($preferred as $value) {
                if (in_array($value, $supported)) {
                    App::setLocale($value);
                    Session::put('locale', $value);
                    break;
                }
            }
        }
        return $next($request);
    }

    private function parseAcceptLanguage(Request $request)
    {
        // e.g. "nl,en;q=0.9,en-US;q=0.8"
        $list = explode(',', $request->server('HTTP_ACCEPT_LANGUAGE', ''));

        $locales = Collection::make($list)
            ->map(function ($locale) {
                // e.g. "en;q=0.9"
                $parts = explode(';', $locale);

                $mapping['locale'] = trim($parts[0]);

                if (isset($parts[1])) {
                    $factorParts = explode('=', $parts[1]);

                    $mapping['factor'] = $factorParts[1];
                } else {
                    $mapping['factor'] = 1;
                }

                return $mapping;
            })
            ->sortByDesc(function ($locale) {
                return $locale['factor'];
            });

        return $locales->map(fn($l) => $l['locale']);
    }
}
