<?php

namespace Database\Factories;

use App\Models\Maker;
use Illuminate\Database\Eloquent\Factories\Factory;

class MakerFactory extends Factory
{
    protected $model = Maker::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->company(),
        ];
    }
}
