<?php

namespace Database\Factories;

use App\Models\Jurusan;
use Illuminate\Database\Eloquent\Factories\Factory;

class JurusanFactory extends Factory
{
    protected $model = Jurusan::class;

    public function definition(): array
    {
        return [
            'published' => true,
            'title' => $this->faker->unique()->words(3, true),
            'short_name' => strtoupper($this->faker->lexify('???')),
            'accreditation' => $this->faker->randomElement(['A', 'B', 'C']),
            'excerpt' => $this->faker->sentence(15),
        ];
    }
}
