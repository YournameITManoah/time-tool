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
        $start = $this->faker->date();
        return [
            'client_id' => Client::factory(),
            'name' => $this->faker->unique()->word(),
            'start_date' => $this->faker->date(),
            'end_date' => $this->faker->dateTimeBetween($start)->format('Y-m-d'),
            'available_hours' => $this->faker->numberBetween(),
        ];
    }
}
