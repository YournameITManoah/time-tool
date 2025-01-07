<?php

namespace App\Rules;

use App\Models\TimeLog;
use Closure;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ValidationRule;

class UniqueTimeLogFrame implements DataAwareRule, ValidationRule
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
        $values = array_filter([$this->data['date'] ?? null, $this->data['start_time'] ?? null, $this->data['stop_time'] ?? null]);
        if (!$this->currentTimeLog && count($values) < 3) {
            return;
        }

        // Time logs should not overlap
        if (TimeLog::where('user_id', $this->data['user_id'] ?? $this->currentTimeLog?->user_id ?? \Auth::id())
            ->where('id', '!=', $this->currentTimeLog?->id)
            ->whereDate('date', $this->data['date'] ?? $this->currentTimeLog?->date)
            ->where(function (Builder $query) use ($value) {
                $query->contains($value)->orWhere(function (Builder $query) {
                    $query->partOf($this->data['start_time'] ?? $this->currentTimeLog?->start_time, $this->data['stop_time'] ?? $this->currentTimeLog?->stop_time);
                });
            })
            ->exists()
        ) {
            $fail('messages.valid_time_log_frame')->translate(['attribute' => $attribute]);
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
