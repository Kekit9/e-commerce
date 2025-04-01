<?php

namespace Database\Factories;

use App\Models\Maker;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->word(),
            'description' => $this->faker->sentence(),
            'maker_id' => Maker::factory(),
            'price' => $this->faker->randomFloat(2, 100, 1000),
            'category' => $this->faker->word(),
            'ordered_at' => $this->faker->dateTimeThisDecade(),
        ];
    }
}
