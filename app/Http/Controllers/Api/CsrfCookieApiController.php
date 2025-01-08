<?php

namespace App\Http\Controllers\Api;


class CsrfCookieApiController extends ApiController
{
    /**
     * Start a secure session
     *
     * This endpoint will start a session and create a `XSRF-TOKEN` cookie. Include the `X-XSRF_TOKEN` header to every other request with the value of the `XSRF-TOKEN` cookie.
     *
     * @unauthenticated
     */
    public function index()
    {
        return response()->noContent();
    }
}
