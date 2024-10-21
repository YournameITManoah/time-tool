<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StoreTimeLogRequest;

class TimeLogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return hybridly(component: 'time.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return hybridly(component: 'time.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTimeLogRequest $request)
    {
        // Retrieve the validated input data
        $validated = $request->validated();

        // Store the time log
        Logger('Create time log', $validated);

        // Redirect to overview
        return redirect()->route('time-log.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        return hybridly(component: 'time.edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $message = sprintf('Successfully updated %s', 'Time Log');

        return redirect()->back()->with('success', $message);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $message = sprintf('Successfully deleted %s', 'Time Log');

        return redirect()->back()->with('success', $message);
    }
}
