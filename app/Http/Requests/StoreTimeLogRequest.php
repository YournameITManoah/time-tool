<?php

namespace App\Http\Requests;

use App\Rules\Time;
use App\Rules\UniqueTimeLogFrame;
use App\Rules\MaxProjectHours;
use App\Rules\ValidProjectDate;
use App\Rules\ValidConnection;
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
        $current = $this->route('time_log');
        return [
            'project_id' => [
                $this->getMethod() == 'PATCH' ? 'sometimes' : 'required',
                'integer',
                Rule::exists('connections')->where(function (Builder $query) {
                    return $query->where('user_id', \Auth::id());
                }),
                new MaxProjectHours($current)
            ],
            'task_id' => [$this->getMethod() == 'PATCH' ? 'sometimes' : 'required', 'integer', new ValidConnection()],
            'date' => [$this->getMethod() == 'PATCH' ? 'sometimes' : 'required', 'date_format:Y-m-d', 'after_or_equal:1 year ago', 'before_or_equal:now', new ValidProjectDate($current)],
            'start_time' => [$this->getMethod() == 'PATCH' ? 'sometimes' : 'required', new Time(), new UniqueTimeLogFrame($current)],
            'stop_time' => [$this->getMethod() == 'PATCH' ? 'sometimes' : 'required', new Time(), 'after:start_time', new UniqueTimeLogFrame($current)],
            'comments' => ['sometimes', 'nullable', 'string', 'max:200']
        ];
    }
}
