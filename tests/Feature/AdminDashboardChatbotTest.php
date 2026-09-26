<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminDashboardChatbotTest extends TestCase
{
  use RefreshDatabase;

  public function test_admin_dashboard_displays_real_ai_chatbot_widget(): void
  {
    $admin = User::factory()->create([
      'role' => 'admin',
    ]);

    $this->actingAs($admin)
      ->get(route('admin.dashboard'))
      ->assertOk()
      ->assertSee('الشات بوت الذكي')
      ->assertSee('admin.chatbot.send');
  }
}
