<?php

namespace App\Rules;

use App\Models\Project;
use App\Models\TimeLog;
use Closure;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ValidationRule;

class MaxProjectHours implements DataAwareRule, ValidationRule
{
    /**
     * All of the data under validation.
     *
     * @var array<string, mixed>
     */
    protected $data = [];
    protected $currentTimeLog;

    public function __construct(?TimeLog $currentTimeLog = null)
    {
        $this->currentTimeLog = $currentTimeLog;
    }
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // If required values are missing, skip validation
        $values = array_filter([$value ?? null]);
        if (!$this->currentTimeLog && count($values) < 1) {
            return;
        }

        $projectId = $value ?? $this->currentTimeLog?->project_id;
        $project = Project::find($projectId);
        if ($project->available_hours == null) {
            return;
        }

        $newTime = (strtotime($this->data['stop_time'] ?? $this->currentTimeLog?->stop_time) - strtotime($this->data['start_time'] ?? $this->currentTimeLog?->start_time)) / 3600;
        $usedHours = array_sum(TimeLog::where('project_id', '=', $projectId)->where('id', '!=', $this->currentTimeLog?->id)->get()->map(fn($timeLog) => $timeLog->duration)->toArray()) / 3600;
        $remainingHours = $project->available_hours - $usedHours;

        \Log::info(print_r(['newTime' => $newTime, 'userHours' => $usedHours, 'remaining' => $remainingHours], true));

        // Projects should not exceed the available hours
        if ($remainingHours < $newTime) {
            $fail('messages.valid_project_hours')->translate(['attribute' => __("validation.attributes.$attribute"), 'remaining' => round($remainingHours, 2, PHP_ROUND_HALF_DOWN), 'max' => $project->available_hours]);
        }
    }

    /**
     * Set the data under validation.
     *
     * @param  array<string, mixed>  $data
     */
    public function setData(array $data): static
    {
        $this->data = $data;

        return $this;
    }
}
