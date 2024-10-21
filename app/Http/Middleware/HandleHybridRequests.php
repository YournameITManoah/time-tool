<?php

namespace App\Http\Middleware;

use App\Data\AuthData;
use App\Data\SharedData;
use App\Data\UserData;
use App\Models\User;
use Hybridly\Http\Middleware;

class HandleHybridRequests extends Middleware
{
    /**
     * Defines the properties that are shared to all requests.
     */
    public function share(): SharedData
    {
        $user = auth()->user();
        $can = null;

        if ($user !== null) {
            $can = [
                'view-admin' => $user->can('view-admin', User::class)
            ];
        }

        return SharedData::from([
            'auth' => AuthData::from([
                'user' => UserData::optional($user),
                'can' => $can
            ]),
        ]);
    }
}
