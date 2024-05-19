<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Lot>
 */
class LotFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::get()->random()->id,
            'title' => fake()->word(),
            'description' => fake()->word(),
            'status' => fake()->word(),
            'cost' => fake()->numberBetween(1000, 10000),
            'bet' => fake()->numberBetween(1000, 10000),
            'bet_step' => fake()->numberBetween(10, 100),
            'category_id' => Category::get()->random()->id,
        ];
    }
}
