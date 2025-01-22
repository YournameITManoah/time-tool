<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Project;
use App\Models\TimeLog;
use App\Models\Task;
use App\Models\User;

class TimeLogFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = TimeLog::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        $date = $this->faker->dateTimeBetween('-1 years');
        $startTimeMin = clone $date;
        $startTimeMax = clone $date;
        $start = $this->faker->dateTimeBetween($startTimeMin->setTime(8, 0), $startTimeMax->setTime(16, 30));
        $end = $this->faker->dateTimeBetween($start, $startTimeMax->setTime(17, 0));

        return [
            'user_id' => User::factory(),
            'project_id' => Project::factory(),
            'task_id' => Task::factory(),
            'date' => $date->format('Y-m-d'),
            'start_time' => $start->format('H:i'),
            'stop_time' => $end->format('H:i'),
            'comments' => $this->faker->boolean() ? $this->faker->text() : null,
        ];
    }
}
