<?php

namespace Database\Seeders;

use App\Models\Maker;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use App\Models\Service;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(CurrencyRatesSeeder::class);
        $makers = Maker::factory(50)->create();

        $products = Product::factory(150)->create([
            'maker_id' => fn () => $makers->random()->id
        ]);

        $services = Service::factory(50)->create();

        $products->each(function ($product) use ($services) {
            $product->services()->attach(
                $services->random(rand(1, 3))->pluck('id')->toArray()
            );
        });

        User::query()->create([
            'name' => 'Super Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('12345678'),
            'role' => 'admin',
        ]);
    }
}
