<?php

namespace App\Rules;

use App\Models\Project;
use App\Models\TimeLog;
use Closure;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidProjectDate implements DataAwareRule, ValidationRule
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
        $values = array_filter([$value ?? null, $this->data['project_id'] ?? null]);
        if (!$this->currentTimeLog && count($values) < 2) {
            return;
        }

        $project = Project::find($this->data['project_id'] ?? $this->currentTimeLog?->project_id);

        if ($project->start_date !== null && $project->start_date > $this->data['date'] ?? $this->currentTimeLog?->date) {
            $fail('validation.after_or_equal')->translate(['attribute' => __("validation.attributes.$attribute"), 'date' => $project->start_date->format('d-m-Y')]);
        }

        if ($project->end_date !== null && $project->end_date < $this->data['date'] ?? $this->currentTimeLog?->date) {
            $fail('validation.before_or_equal')->translate(['attribute' => __("validation.attributes.$attribute"), 'date' => $project->end_date->format('d-m-Y')]);
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
