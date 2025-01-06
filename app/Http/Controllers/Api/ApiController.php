<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

class ApiController extends Controller
{
    private function response(int $status, string $message)
    {
        return response()->json(['status' => $status, 'message' => $message], $status);
    }

    public function badRequest()
    {
        return $this->response(400, 'Bad Request');
    }

    public function forbidden()
    {
        return $this->response(403, 'Forbidden');
    }

    public function notFound()
    {
        return $this->response(404, 'Not Found');
    }

    public function conflict()
    {
        return $this->response(409, 'Conflict');
    }
}
