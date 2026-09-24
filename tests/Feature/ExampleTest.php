<?php

namespace Tests\Feature;

use App\Models\FoodItem;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
  use RefreshDatabase;

  /**
   * A basic test example.
   */
  public function test_the_application_returns_a_successful_response(): void
  {
    $response = $this->get('/');

    $response->assertStatus(200);
  }

  public function test_menu_search_filters_food_items_by_name(): void
  {
    $user = User::factory()->create([
      'role' => 'customer',
    ]);

    FoodItem::factory()->create([
      'name' => 'مكرونة بالجبنة',
      'description' => 'وجبة لذيذة',
    ]);

    FoodItem::factory()->create([
      'name' => 'برجر لحم',
      'description' => 'وجبة غنية',
    ]);

    $this->actingAs($user)
      ->get('/customer/menu?q=جبنة')
      ->assertOk()
      ->assertSee('مكرونة بالجبنة')
      ->assertDontSee('برجر لحم');
  }
}
