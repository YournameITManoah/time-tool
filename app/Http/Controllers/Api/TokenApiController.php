<?php

namespace App\Http\Controllers\Api;

use App\Models\UserTask;
use Illuminate\Http\Request;

/**
 * API controller user user tasks
 */
class TokenApiController extends ApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return ['tokens' => $request->user()->tokens];
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $token = $request->user()->createToken($request->token_name);
        return ['token' => $token->plainTextToken];
    }
}
