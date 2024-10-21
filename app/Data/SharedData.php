<?php

namespace App\Data;

use Spatie\LaravelData\Data;

class SharedData extends Data
{
    public function __construct(
        public readonly AuthData $auth,
        public readonly ?FlashBagData $flash
    ) {}
}
