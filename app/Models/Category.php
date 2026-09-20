<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory; // 1. استيراد الخاصية
use Illuminate\Database\Eloquent\Model;

class Category extends Model {
    use HasFactory; // 2. تفعيل الخاصية داخل الموديل

    protected $fillable = ['name', 'slug'];

    public function foodItems()
    {
        return $this->hasMany(FoodItem::class);
    }

    /**
     * علاقة المشروبات التابعة للقسم
     */
    public function beverages()
    {
        return $this->hasMany(Beverage::class);
    }
}