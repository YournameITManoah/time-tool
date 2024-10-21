<?php

namespace App\Data;

use Spatie\LaravelData\Data;

class AuthData extends Data
{
    public function __construct(
        public readonly ?UserData $user,
        public readonly ?array $can
    ) {}
}
