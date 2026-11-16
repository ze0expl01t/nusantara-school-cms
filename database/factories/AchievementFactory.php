<?php

namespace Database\Factories;

use App\Models\Achievement;
use Illuminate\Database\Eloquent\Factories\Factory;

class AchievementFactory extends Factory
{
    protected $model = Achievement::class;

    public function definition(): array
    {
        return [
            'published' => true,
            'title' => $this->faker->sentence(4),
            'level' => $this->faker->randomElement(['provinsi', 'nasional', 'kabupaten']),
            'rank' => $this->faker->randomElement(['juara-1', 'juara-2', 'juara-3']),
            'achievement_date' => $this->faker->date(),
        ];
    }
}
