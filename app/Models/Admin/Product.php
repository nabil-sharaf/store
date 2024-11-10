<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
        'sku_code',
        'prefix_id',
    ];

    protected static function boot()
    {
        parent::boot();

        static::created(function ($product) {

            // توليد SKU بعد إنشاء المنتج
            $product->updateQuietly(['sku_code' => $product->generateSku()]);
        });

        // تحديث الكود عند تعديل المنتج وتغيير البريفكس
        static::updated(function ($product) {
            if ($product->isDirty('prefix_id')) { // فقط إذا تغير البريفكس
                $product->updateQuietly(['sku_code' => $product->generateSku()]);
            }
        });
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function images()
    {
        return $this->morphMany(Image::class, 'imageable');
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
                ->where('end_date', '>=', $now)
                ->whereNotNull('product_id');
        });
    }


    public function getDiscountedPriceAttribute()
    {
        // إذا كان المنتج يحتوي على فاريانتات، لا يتم حساب سعر الخصم من هنا
        if ($this->hasVariants()) {
            return null; // ستعتمد عملية الحساب على الفاريانتات الفردية
        }
        $userType = auth()->user()?->customer_type;

        $price = $userType == 'goomla' ? $this->goomla_price : $this->price;
        $discount = $this->discount;

        if ($discount) {
            if ($discount->discount_type === 'percentage') {
                return max($price - ($price * ($discount->discount / 100)), 0);
            } elseif ($discount->discount_type === 'fixed') {
                return max($price - $discount->discount, 0);
            }
        }

        return $price;
    }

    public function getProductPriceAttribute()
    {
        $userType = auth()->user()?->customer_type;
        $price = $userType == 'goomla' ? $this->goomla_price : $this->price;

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

    public function getCustomerOfferAttribute()
    {
        $user = auth()->user();
        if ($user) {
            $customerType = $user->customer_type;
        } else {
            $customerType = 'regular';
        }
        $offers = $this->offers()
            ->where(function ($query) use ($customerType) {
                $query->where('customer_type', $customerType)
                    ->orWhere('customer_type', 'all'); // جلب العروض للجميع
            })
            ->where(function ($query) {
                $query->where('start_date', '<=', now()->endOfDay()) // تأكد أن العرض بدأ في السابق أو في اليوم
                ->where('end_date', '>=', now()->startOfDay()); // تأكد أن العرض لم ينتهي بعد أو في اليوم
            })
            ->first();
        if ($offers) {

            return $offers->offer_name;
        } else {
            return null;
        }
    }

    public function variants(): HasMany
    {
        return $this->hasMany(Variant::class);
    }

    // للتحقق إذا كان المنتج يحتوي على فاريانتات
    public function hasVariants()
    {
        return $this->variants()->exists();
    }

    // للحصول على المخزون المتاح (إما من جدول `products` أو من `variants`)
    public function getAvailableQuantityAttribute()
    {
        return $this->hasVariants() ? $this->variants()->sum('quantity') : $this->quantity;
    }

    public function prefix()
    {
        return $this->belongsTo(Prefix::class);
    }

    private function generateSku()
    {
        $prefixCode = strtoupper($this->prefix->prefix_code) ?? 'MAMA-'; // استخدم البريفكس المختار أو "mama-" إذا لم يكن موجودًا
        $sku = $prefixCode . str_pad($this->id, 3, '0', STR_PAD_LEFT); // تكوين SKU
        return $sku;
    }

}

