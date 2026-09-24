<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminDashboardTest extends TestCase
{
  use RefreshDatabase;

  public function test_admin_dashboard_shows_customer_order_details_and_filters_by_customer_and_status(): void
  {
    $admin = User::factory()->create(['role' => 'admin']);
    $customer = User::factory()->create(['name' => 'John Doe', 'email' => 'john@example.com', 'role' => 'customer']);

    Order::create([
      'user_id' => $customer->id,
      'total_price' => 95.50,
      'status' => 'pending',
      'phone' => '0501234567',
      'delivery_address' => 'Street 12, Riyadh',
    ]);

    Order::create([
      'user_id' => $customer->id,
      'total_price' => 45.00,
      'status' => 'completed',
      'phone' => '0501111111',
      'delivery_address' => 'Another address',
    ]);

    $response = $this->actingAs($admin)->get('/admin/dashboard?customer_name=John&status=pending');

    $response->assertOk();
    $response->assertSee('John Doe');
    $response->assertSee('0501234567');
    $response->assertSee('Street 12, Riyadh');
    $response->assertDontSee('Another address');
  }
}
