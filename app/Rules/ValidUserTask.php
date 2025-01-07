<?php

namespace App\Rules;

use App\Models\UserTask;
use App\Models\TimeLog;
use Closure;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidUserTask implements DataAwareRule, ValidationRule
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

        // Tasks should be linked to the specified project and user
        if (UserTask::where('task_id', $value)
            ->where('project_id',  $this->data['project_id'] ?? $this->currentTimeLog?->project_id)
            ->where('user_id', $this->data['user_id'] ?? $this->currentTimeLog?->user_id ?? \Auth::id())
            ->doesntExist()
        ) {
            $fail('messages.valid_user_task')->translate(['attribute' => $attribute]);
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
