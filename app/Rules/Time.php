<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class Time implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $formats = ['H:i:s', 'H:i'];

        foreach ($formats as $format) {
            $parsed = date_parse_from_format($format, $value);

            if ($parsed['error_count'] === 0 && $parsed['warning_count'] === 0) {
                return;
            }
        }

        $fail('messages.valid_time_format')->translate(['attribute' => $attribute]);
    }
}
