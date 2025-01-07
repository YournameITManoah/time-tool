<?php

namespace App\Data;

use Spatie\LaravelData\Data;

/**
 * Data about the authentication and authorization of the logged in user
 */
class AuthData extends Data
{
    public function __construct(
        public readonly ?UserData $user,
        public readonly ?array $can
    ) {}
}
