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
        'is_best_seller',
        'info',
    ];

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function images()
    {
        return $this->hasMany(Image::class);
    }

    public function orderDetails()
    {
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


    public function getDiscountedPriceAttribute()
    {
        $userType = auth()->user()?->customer_type;

        $price = $userType =='goomla' ? $this->goomla_price : $this->price;
        $discount = $this->discount;

        if ($discount) {
            if ($discount->discount_type === 'percentage') {
                return max($price - ($price * ($discount->discount / 100)) ,0) ;
            }
            elseif($discount->discount_type === 'fixed') {
                return max($price - $discount->discount, 0);
            }
        }

        return $price;
    }

    public function getProductPriceAttribute()
    {
        $userType = auth()->user()?->customer_type;
        $price = $userType =='goomla' ? $this->goomla_price : $this->price;

        return $price;
    }

    public function offers()
    {
        return $this->hasMany(Offer::class);
    }

    public function getOfferDetails($customerType = 'regular')
    {
        return $this->offers()
            ->where(function ($query) use ($customerType) {
                $query->where('customer_type', $customerType)
                    ->orWhere('customer_type', 'all'); // جلب العروض للجميع
            })
            ->where(function ($query) {
                $query->where('start_date', '<=', now()->endOfDay()) // تأكد أن العرض بدأ في السابق أو في اليوم
                ->where('end_date', '>=', now()->startOfDay()); // تأكد أن العرض لم ينتهي بعد أو في اليوم
            })
            ->first();
    }

    public function getCustomerOfferAttribute(){
        $user = auth()->user();
        if($user){
            $customerType = $user->customer_type;
        }else{
            $customerType = 'regular';
        }
        $offers =  $this->offers()
            ->where(function ($query) use ($customerType) {
                $query->where('customer_type', $customerType)
                    ->orWhere('customer_type', 'all'); // جلب العروض للجميع
            })
            ->where(function ($query) {
                $query->where('start_date', '<=', now()->endOfDay()) // تأكد أن العرض بدأ في السابق أو في اليوم
                ->where('end_date', '>=', now()->startOfDay()); // تأكد أن العرض لم ينتهي بعد أو في اليوم
            })
            ->first();
        if($offers){

        return $offers->offer_name;
        }else{
            return  null;
        }
    }
}

