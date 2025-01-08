<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Dedoc\Scramble\Attributes\ExcludeRouteFromDocs;

class ApiController extends Controller
{
    private function response(int $status, string $message)
    {
        return response()->json(['message' => $message], $status);
    }

    public function unauthorized()
    {
        return $this->response(401, 'Unauthenticated.');
    }

    public function forbidden()
    {
        return $this->response(403, 'Forbidden.');
    }

    #[ExcludeRouteFromDocs]
    public function notFound()
    {
        return $this->response(404, 'Not Found.');
    }
}
