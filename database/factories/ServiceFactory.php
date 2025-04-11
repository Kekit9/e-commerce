<?php

namespace Database\Factories;

use App\Models\Service;
use Illuminate\Database\Eloquent\Factories\Factory;

class ServiceFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Service::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'service_type' => $this->faker->word(),
            'duration' => $this->faker->numberBetween(1, 12),
            'price' => $this->faker->randomFloat(2, 100, 1000),
            'terms' => $this->faker->sentence(),
        ];
    }
}

