<?php

namespace App\Http\Controllers\Api;

use App\Models\TimeLog;
use App\Http\Requests\StoreTimeLogRequest;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class TimeLogApiController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @response LengthAwarePaginator<TimeLog>
     */
    public function index(Request $request)
    {
        // Check if user is authorized
        \Gate::authorize('viewAny', TimeLog::class);

        // Defaults
        $defaultLimit = 10;
        $defaultSort = [
            'key' => 'date',
            'order' => 'desc'
        ];

        // Query params
        $perPage = $request->get('limit', $defaultLimit);
        $sortBy = $request->get('sort', $defaultSort);

        // -1 means fetch all
        if ($perPage === '-1') {
            $perPage = '1000';
        }
        ;

        // Get the time logs of the logged in user
        $timeLogs = TimeLog::query()
            ->where('user_id', \Auth::id())
            ->with('project:id,name')
            ->with('task:id,name')
            ->orderBy($sortBy['key'], $sortBy['order'])
            ->orderBy('date', 'desc')
            ->orderBy('start_time', 'desc')
            ->paginate($perPage);

        return $timeLogs;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTimeLogRequest $request)
    {
        // Check if user is authorized
        \Gate::authorize('create', TimeLog::class);

        // Retrieve the validated input data
        $validated = $request->validated();

        // Store the time log and return it

        /**
         * A time log resource.
         *
         * @status 201
         * @body TimeLog
         */
        return TimeLog::create([
            ...$validated,
            'user_id' => \Auth::id(),
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, TimeLog $timeLog)
    {
        // Check if user is authorized
        \Gate::authorize('view', $timeLog);

        // Return requested time log
        return $timeLog;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreTimeLogRequest $request, TimeLog $timeLog)
    {
        // Check if user is authorized
        \Gate::authorize('update', $timeLog);

        // Retrieve the validated input data
        $validated = $request->validated();

        // Update the time log
        $timeLog->updateOrFail($validated);

        return $timeLog;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, TimeLog $timeLog)
    {
        // Check if user is authorized
        \Gate::authorize('delete', $timeLog);

        // Delete the time log
        $timeLog->delete();

        return response()->noContent();
    }
}
