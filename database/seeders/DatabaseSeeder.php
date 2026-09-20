<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Category;
use App\Models\FoodItem;
use App\Models\Beverage;
use App\Models\CustomerPreference;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder {
    public function run(): void {
        // 1. حساب أدمن ثابت للتسجيل به
        User::create([
            'name' => 'Admin Manager',
            'email' => 'admin@cafeteria.com',
            'password' => Hash::make('123456'),
            'role' => 'admin',
        ]);

        // 2. إنشاء 5 أقسام، وكل قسم بداخله 4 أطعمة و 3 مشروبات عشوائية
        Category::factory(5)->create()->each(function ($category) {
            FoodItem::factory(4)->create(['category_id' => $category->id]);
            Beverage::factory(3)->create(['category_id' => $category->id]);
        });

        // 3. إنشاء 10 عملاء عشوائيين وإنشاء تفضيلات خاصة بكل عميل
        User::factory(10)->create(['role' => 'customer'])->each(function ($user) {
            CustomerPreference::factory()->create(['user_id' => $user->id]);
        });
    }
}