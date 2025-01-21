<?php

namespace App\Http\Controllers\Api;

use App\Models\UserTask;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class UserTaskApiController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @response LengthAwarePaginator<UserTask>
     */
    public function index(Request $request)
    {
        // Check if user is authorized
        \Gate::authorize('viewAny', UserTask::class);

        // Defaults
        $defaultLimit = 10;
        $perPage = $request->get('limit', $defaultLimit);

        // -1 means fetch all
        if ($perPage === '-1') {
            $perPage = '1000';
        }

        // Get the user tasks of the logged in user
        $userTasks = UserTask::query()
            ->where('user_id', \Auth::id())
            ->when($request->get('sort'), function ($query, $sortBy) {
                return $query->orderBy($sortBy['key'], $sortBy['order']);
            })
            ->with(['project:id,name,client_id,start_date,end_date', 'task:id,name', 'project.client:id,name'])
            ->paginate($perPage);

        return $userTasks;
    }
}
