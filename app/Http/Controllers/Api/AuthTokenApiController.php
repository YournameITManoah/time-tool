<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use Dedoc\Scramble\Attributes\HeaderParameter;

class AuthTokenApiController extends ApiController
{
    /**
     * Request a Personal Access Token
     *
     * This endpoint will create a Personal Access Token for the user with the provided email. Include the User Access Token as a Bearer Token to every request that requires authentication.
     *
     * @unauthenticated
     */
    #[HeaderParameter('Accept-Language', description: 'The preferred language.', type: 'string', default: 'system locale', example: 'nl,en-US;q=0.7,en;q=0.3')]
    public function store(Request $request)
    {
        $request->validate(['api_key' => 'required|string', 'email' => 'required|email']);

        if ($request->api_key != \Config::get('app.api_key')) {
            abort(401, 'No valid api key provided.');
        }

        $user = User::where('email', $request->email)->firstOrFail();

        $tokenName = $request->headers->get('origin');

        // Revoke old token if existing
        $user->tokens()->where('name', $tokenName)->delete();

        // Create new token
        $token = $user->createToken($tokenName);

        return ['token' => $token->plainTextToken];
    }
}
