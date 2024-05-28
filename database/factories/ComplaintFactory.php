<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;


class ComplaintFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'author'=> User::get()->random()->id,
            'target'=> User::get()->random()->id,
            'content'=> fake()->sentence,
            'viewed'=>fake()->numberBetween(0,1),
            'decision'=>fake()->numberBetween(0,1),
        ];
    }
}
