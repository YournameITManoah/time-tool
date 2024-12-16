<?php

namespace App\Http\Controllers\Api;

use App\Models\TimeLog;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TimeLogApiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $defaultLimit = 10;
        $defaultSort = [
            'key' => 'date',
            'order' => 'desc'
        ];

        $perPage = $request->get('limit', $defaultLimit);
        $sortBy = $request->get('sort', $defaultSort);

        // -1 means fetch all
        if ($perPage === '-1') {
            $perPage = '1000';
        };

        $timeLogs = TimeLog::query()
            ->where('user_id', auth()->id())
            ->with('project:id,name')
            ->with('task:id,name')
            ->orderBy($sortBy['key'], $sortBy['order'])
            ->orderBy('date', 'desc')
            ->orderBy('start_time', 'desc')
            ->paginate($perPage);

        return response()->json($timeLogs);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
