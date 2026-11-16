<?php

namespace Database\Factories;

use App\Models\JobVacancy;
use Illuminate\Database\Eloquent\Factories\Factory;

class JobVacancyFactory extends Factory
{
    protected $model = JobVacancy::class;

    public function definition(): array
    {
        return [
            'published' => true,
            'title' => $this->faker->jobTitle(),
            'company_name' => $this->faker->company(),
            'location' => $this->faker->city(),
            'job_type' => $this->faker->randomElement(['full-time', 'part-time', 'kontrak', 'magang']),
            'deadline' => $this->faker->dateTimeBetween('now', '+3 months')->format('Y-m-d'),
        ];
    }
}
