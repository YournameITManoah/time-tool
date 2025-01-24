<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Dedoc\Scramble\Attributes\ExcludeRouteFromDocs;

class ApiController extends Controller
{
    public function unauthorized()
    {
        abort(401, 'Unauthorized.');
    }

    public function forbidden()
    {
        abort(403, 'Forbidden.');
    }

    #[ExcludeRouteFromDocs]
    public function notFound()
    {
        abort(404, 'Not Found.');
    }
}
