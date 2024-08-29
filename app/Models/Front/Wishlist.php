<?php

namespace App\Models\Front;

use App\Models\Admin\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Wishlist extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'product_id',
    ];

    // علاقة مع نموذج User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // علاقة مع نموذج Product
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
