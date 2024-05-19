<?php

namespace Database\Factories;

use App\Models\Lot;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Photo>
 */
class PhotoFactory extends Factory
{

    public function definition(): array
    {
        return [
            'lot_id'=>Lot::get()->random()->id,
            'adress'=>fake()->word,
        ];
    }
}
