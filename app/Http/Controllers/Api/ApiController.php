<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Dedoc\Scramble\Attributes\HeaderParameter;
use Dedoc\Scramble\Attributes\ExcludeRouteFromDocs;

#[HeaderParameter('Accept-Language', description: 'The preferred language.', type: 'string', default: 'en-US,en;q=0.7', example: 'nl,en-US;q=0.7,en;q=0.3')]
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
