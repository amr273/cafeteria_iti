<?php

namespace Database\Factories;

use App\Models\CustomerPreference;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
/**
 * @extends Factory<CustomerPreference>
 */
class CustomerPreferenceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'spicy_level' => $this->faker->numberBetween(0, 5),
            'price_preference' => $this->faker->randomFloat(2, 50, 200),
            'preferred_taste' => $this->faker->randomElement(['حار وجبن', 'حلو', 'مشويات', 'خفيف']),
        ];
    }
}
