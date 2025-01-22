<?php

namespace App\Http\Controllers\Api;

use App\Models\Connection;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class ConnectionApiController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @response LengthAwarePaginator<Connection>
     */
    public function index(Request $request)
    {
        // Check if user is authorized
        \Gate::authorize('viewAny', Connection::class);

        // Defaults
        $defaultLimit = 10;
        $perPage = $request->get('limit', $defaultLimit);

        // -1 means fetch all
        if ($perPage === '-1') {
            $perPage = '1000';
        }

        // Get the connections of the logged in user
        $connections = Connection::query()
            ->where('user_id', \Auth::id())
            ->with(['project:id,name,client_id,start_date,end_date', 'task:id,name', 'project.client:id,name'])
            ->whereHas('project', function ($query) {
                return $query->where(function ($query) {
                    return $query->whereNull('start_date')->orWhereDate('start_date', '<=', now());
                })
                    ->where(function ($query) {
                        return $query->whereNull('end_date')->orWhereDate('end_date', '>=', now());
                    });
            })
            ->when($request->get('sort'), function ($query, $sortBy) {
                return $query->orderBy($sortBy['key'], $sortBy['order']);
            })
            ->paginate($perPage);

        return $connections;
    }
}
