<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Client;
use App\Models\Project;

class ProjectFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Project::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        $start = $this->faker->boolean() ? $this->faker->dateTimeBetween('- 2 years', '+ 1 year') : null;
        return [
            'client_id' => Client::factory(),
            'name' => $this->faker->unique()->word(),
            'start_date' => $start,
            'end_date' => $start ? $this->faker->dateTimeBetween($start, $start->format('d-m-Y') . '+ 1 year')->format('Y-m-d') : null,
            'available_hours' => $this->faker->randomElement([null, 16, 32, 64, 128]),
        ];
    }
}
