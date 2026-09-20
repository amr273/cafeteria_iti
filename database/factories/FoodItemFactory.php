<?php

namespace Database\Factories;

use App\Models\FoodItem;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Category;
/**
 * @extends Factory<FoodItem>
 */
class FoodItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'category_id' => Category::factory(),
            'name' => $this->faker->words(2, true),
            'description' => $this->faker->sentence(),
            'price' => $this->faker->randomFloat(2, 50, 300),
            'spicy_level' => $this->faker->numberBetween(0, 5),
            'available_quantity' => $this->faker->numberBetween(0, 20),
            'status' => true,
        ];
    }
}
