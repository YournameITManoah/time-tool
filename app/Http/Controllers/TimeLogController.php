<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TimeLog;
use App\Http\Requests\StoreTimeLogRequest;
use Illuminate\Support\Facades\Gate;

class TimeLogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Check if user is authorized
        Gate::authorize('viewAny', TimeLog::class);

        // Return view
        return hybridly(component: 'time.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Check if user is authorized
        Gate::authorize('create', TimeLog::class);

        // return view
        return hybridly(component: 'time.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTimeLogRequest $request)
    {
        // Check if user is authorized
        Gate::authorize('create', TimeLog::class);

        // Retrieve the validated input data
        $validated = $request->validated();

        // Store the time log
        TimeLog::create([
            ...$validated,
            'user_id' => \Auth::id(),
        ]);

        // Redirect to overview
        return redirect()->route('time-log.index')->with('success', __('messages.time_log_saved'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TimeLog $timeLog)
    {
        // Check if user is authorized
        Gate::authorize('update', $timeLog);

        // Return view
        return hybridly(
            component: 'time.edit',
            properties: ['timeLog' => $timeLog]
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreTimeLogRequest $request, TimeLog $timeLog)
    {
        // Check if user is authorized
        Gate::authorize('update', $timeLog);

        // Retrieve the validated input data
        $validated = $request->validated();

        // Update the time log
        $timeLog->updateOrFail($validated);

        // Redirect back with success message
        return redirect()->back()->with('success', __('messages.time_log_saved'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TimeLog $timeLog)
    {
        // Check if user is authorized
        Gate::authorize('delete', $timeLog);

        // Delete time log
        $timeLog->delete();

        // Redirect back with success message
        return redirect()->back()->with('success', __('messages.time_log_removed'));
    }
}
