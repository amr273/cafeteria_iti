<?php

namespace Tests\Feature;

use App\Models\Beverage;
use App\Models\CustomerPreference;
use App\Models\FoodItem;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CustomerRecommendationsUsePreferencesTest extends TestCase
{
  use RefreshDatabase;

  public function test_customer_recommendations_are_filtered_by_saved_preferences(): void
  {
    $customer = User::factory()->create(['role' => 'customer']);

    CustomerPreference::factory()->create([
      'user_id' => $customer->id,
      'spicy_level' => 4,
      'price_preference' => 60,
      'preferred_taste' => 'حار وجبن',
    ]);

    $matchingFood = FoodItem::factory()->create([
      'name' => 'برجر حار وجبن',
      'price' => 45,
      'spicy_level' => 4,
      'description' => 'وجبة حارة وجبن',
      'status' => true,
    ]);

    $nonMatchingFood = FoodItem::factory()->create([
      'name' => 'كيك الحلوى',
      'price' => 120,
      'spicy_level' => 0,
      'description' => 'حلو ومناسب للمرح',
      'status' => true,
    ]);

    $matchingBeverage = Beverage::factory()->create([
      'name' => 'عصير برتقال حار',
      'price' => 30,
      'temperature' => 'cold',
      'status' => true,
    ]);

    $response = $this->actingAs($customer)->get('/customer/recommendations');

    $response->assertOk();
    $response->assertSee($matchingFood->name);
    $response->assertSee($matchingBeverage->name);
    $response->assertDontSee($nonMatchingFood->name);
  }
}
