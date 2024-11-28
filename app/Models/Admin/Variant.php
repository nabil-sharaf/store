<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use OwenIt\Auditing\Contracts\Auditable;

class Variant extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = ['product_id', 'sku_code', 'price', 'goomla_price', 'quantity'];

//    protected $auditExclude = [
//        'id',
//        'product_id'
//    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function optionValues(): BelongsToMany
    {
        return $this->belongsToMany(OptionValue::class);
    }

    public function discount()
    {
        return $this->hasOne(ProductDiscount::class)->where(function ($query) {
            $now = now();
            $query->where('start_date', '<=', $now)
                ->where('end_date', '>=', $now)
                // تأكد أن الخصم خاص بالفاريانت
                ->whereNotNull('variant_id');

        });
    }

    public function images()
    {
        return $this->morphMany(Image::class, 'imageable');
    }

    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class);
    }



    public function getDiscountedPriceAttribute()
    {
        $userType = auth()->user()?->customer_type;
        $price = $userType == 'goomla' ? $this->goomla_price : $this->price;
        $discount = $this->discount ?: $this->product->discount;

        if ($discount) {
            if ($discount->discount_type === 'percentage') {
                return max($price - ($price * ($discount->discount / 100)), 0);
            } elseif ($discount->discount_type === 'fixed') {
                return max($price - $discount->discount, 0);
            }
        }

        return $price;
    }

    public function getVariantPriceAttribute()
    {
        $userType = auth()->user()?->customer_type;
        $price = $userType == 'goomla' ? $this->goomla_price : $this->price;

        return $price;
    }

    public function generateSku()
    {
        // جلب البريفكس
        $prefixCode = $this->product?->prefix?->prefix_code ?? 'MAMA';
        $sku = $prefixCode . str_pad($this->id, 3, '0', STR_PAD_LEFT);

        // تأكد من وجود قيم الخيارات
        if ($this->optionValues->isNotEmpty()) {
            // فرز القيم حسب اسم الخيار باللغة الإنجليزية
            $sortedOptionValues = $this->optionValues->sortBy(function ($optionValue) {
                return $optionValue->option->getTranslation('name', 'en');
            });

            // بناء SKU
            foreach ($sortedOptionValues as $optionValue) {
                $optionName = $optionValue->option->getTranslation('name', 'en');
                $firstTowOptionNameLetter = substr($optionName, 0, 1);

                $optionValueText = substr($optionValue->getTranslation('value', 'en'), 0, 2);

                $sku .= '-' . strtoupper($firstTowOptionNameLetter) . '-' . strtoupper($optionValueText);
            }
        }

        return $sku;
    }


}
