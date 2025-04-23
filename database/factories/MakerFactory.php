<?php

namespace Database\Factories;

use App\Models\Maker;
use Illuminate\Database\Eloquent\Factories\Factory;

class MakerFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<Maker>
     */
    protected $model = Maker::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->company(),
        ];
    }
}
