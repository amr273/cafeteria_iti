<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  /**
   * Run the migrations.
   */
  public function up(): void
  {
    Schema::table('orders', function (Blueprint $table) {
      $table->string('phone', 20)->nullable()->after('user_id');
      $table->text('delivery_address')->nullable()->after('phone');
      $table->string('payment_method')->nullable()->after('delivery_address');
      $table->text('notes')->nullable()->after('payment_method');
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::table('orders', function (Blueprint $table) {
      $table->dropColumn(['phone', 'delivery_address', 'payment_method', 'notes']);
    });
  }
};
