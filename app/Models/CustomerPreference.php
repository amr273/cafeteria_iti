<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerPreference extends Model
{
    protected $fillable = ['user_id', 'spicy_level', 'price_preference', 'preferred_taste'];

    use HasFactory;
    public function user() {
        return $this->belongsTo(User::class);
    }
}
