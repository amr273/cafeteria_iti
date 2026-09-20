<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
    'order_id',
    'itemable_id',
    'itemable_type',
    'price',
    'quantity',
    'subtotal', // إضافة الحقل هنا
];

    protected $guarded = [];

    /**
     * علاقة عنصر الطلب مع الطلب الرئيسي
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * علاقة متعددة الأشكال (Polymorphic)
     * تربط العنصر بـ FoodItem أو Beverage حسب نوع المنتج
     */
    public function itemable()
    {
        return $this->morphTo();
    }
}
