<?php

namespace App\Http\Controllers\Api;

use App\Models\UserTask;
use Illuminate\Http\Request;

/**
 * API controller user user tasks
 */
class UserTaskApiController extends ApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Check if user is authorized
        \Gate::authorize('viewAny', userTask::class);

        // Defaults
        $defaultLimit = 10;
        $perPage = $request->get('limit', $defaultLimit);

        // -1 means fetch all
        if ($perPage === '-1') {
            $perPage = '1000';
        };

        // Get the user tasks of the logged in user
        $userTasks = UserTask::query()
            ->where('user_id', \Auth::id())
            ->when($request->get('sort'), function ($query, $sortBy) {
                return $query->orderBy($sortBy['key'], $sortBy['order']);
            })
            ->with('project:id,name')
            ->with('task:id,name')
            ->paginate($perPage);

        return $userTasks;
    }
}
