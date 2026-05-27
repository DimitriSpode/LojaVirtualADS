<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\Type;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        return [
            'name' => fake()->words(3, true),
            'description' => Str::limit(fake()->sentence(12), 255, ''),
            'quantity' => fake()->numberBetween(1, 200),
            'price' => fake()->randomFloat(2, 10, 1000),
            'image' => null,
            'type_id' => Type::inRandomOrder()->first()->id,
        ];
    }
}
