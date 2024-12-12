<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TimeLog extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'project_id',
        'task_id',
        'date',
        'start_time',
        'stop_time',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'user_id' => 'integer',
        'project_id' => 'integer',
        'date' => 'date',
    ];

    public function getDurationAttribute()
    {
        return 0;
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }

    /**
     * Scope a query to only include time logs that contain a specific time value.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $time
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeContains($query, $time)
    {
        return $query->where('start_time', '<', $time)
            ->where('stop_time', '>', $time);
    }

    /**
     * Scope a query to only include time logs that are part of a specific time frame.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $start
     * @param  string  $stop
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePartOf($query, $start, $stop)
    {
        return $query->where('start_time', '>=', $start)
            ->where('stop_time', '<=', $stop);
    }
}
