<?php

namespace App\Models\Admin;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
        protected $fillable = [
        'name',
        'description',
        'quantity',
         'price',
         'goomla_price',
          'is_trend',
    ];
    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function images()
    {
        return $this->hasMany(Image::class);
    }

    public function orderDetails(){
        return $this->hasMany(OrderDetail::class);
    }


    public function discount()
    {
        return $this->hasOne(ProductDiscount::class)->where(function ($query) {
            $now = now();
            $query->where('start_date', '<=', $now)
                ->where('end_date', '>=', $now);
        });
    }
    public function ProductDiscount()
    {
        return $this->hasOne(ProductDiscount::class);


    }


    public function getPriceAfterDiscountAttribute()
    {
        $price = $this->price;
        $discount = $this->discount;

        if ($discount) {
            if ($discount->discount_type === 'percentage') {
                return $price - ($price * ($discount->discount / 100));
            } else {
                return max($price - $discount->discount, 0);
            }
        }

        return $price;
    }
}

