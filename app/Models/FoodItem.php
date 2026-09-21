<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FoodItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'description',
        'price',
        'spicy_level',
        'available_quantity',
        'status',
        'image'
    ];

    protected $guarded = [];

    /**
     * علاقة الوجبة بالقسم الخاضعة له
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * علاقة الوجبة بعناصر الطلبات (Polymorphic)
     */
    public function orderItems()
    {
        return $this->morphMany(OrderItem::class, 'itemable');
    }
}
