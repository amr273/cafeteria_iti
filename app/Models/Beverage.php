<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Beverage extends Model
{
    use HasFactory;
    protected $fillable = ['category_id', 'name', 'price', 'temperature', 'status'];
    protected $guarded = [];

    // علاقة المشروب بالقسم
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
