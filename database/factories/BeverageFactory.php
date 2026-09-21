<?php

namespace Database\Factories;

use App\Models\Beverage;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Category;
/**
 * @extends Factory<Beverage>
 */
class BeverageFactory extends Factory
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
            'price' => $this->faker->randomFloat(2, 20, 100),
            'temperature' => $this->faker->randomElement(['hot', 'cold', 'both']),
            'status' => true,
            'image' => 'foods/sample.jpg'
        ];
    }
}
