<?php

namespace App\Http\Requests;

use App\Models\User;
use App\Rules\Time;
use App\Rules\UniqueTimeLogFrame;
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
        $id = $this->route('time_log')?->id;
        return [
            'project_id' => ['required', Rule::exists('user_tasks')->where(function (Builder $query) {
                return $query->where('user_id', auth()->id());
            })],
            'task_id' => ['required', Rule::exists('user_tasks')->where(function (Builder $query) {
                return $query->where('user_id', auth()->id());
            })],
            'date' => ['required', 'date', 'after_or_equal:1 year ago', 'before_or_equal:now'],
            'start_time' => ['required', new Time(), new UniqueTimeLogFrame($id)],
            'stop_time' => ['required', new Time(), 'after:start_time', new UniqueTimeLogFrame($id)],
        ];
    }
}
