<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Database\Query\Builder;
use Illuminate\Validation\Rule;

class StoreTimeLogRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'project_id' => ['required', Rule::exists('project_user')->where(function (Builder $query) {
                return $query->where('user_id', auth()->id());
            })],
            'date' => ['required', 'date', 'before_or_equal:now'],
            'start_time' => ['required'],
            'stop_time' => ['required', 'after:start_time'],
            'description' => ['required', 'string', 'max:255'],
        ];
    }
}
