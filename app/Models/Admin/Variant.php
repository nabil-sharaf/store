<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Variant extends Model
{
    use HasFactory;

    protected $fillable = ['product_id', 'sku_code', 'price', 'goomla_price', 'quantity'];

// داخل موديل Variant
    protected static function boot()
    {
        parent::boot();
        // عند إنشاء الـ Variant بعد حفظ السجل في قاعدة البيانات
        static::created(function ($variant) {
            // توليد SKU بعد حفظ الـ Variant
            $variant->sku_code = $variant->generateSku();
            // حفظ الـ SKU بعد التعيين
            $variant->save();
        });

        // بعد تحديث الـ Variant
        static::updated(function ($variant) {
            $variant->sku_code = $variant->generateSku();
            $variant->save();
        });
    }


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

    public function images()
    {
        return $this->morphMany(Image::class, 'imageable');
    }

    public function generateSku()
    {
        $prefixCode = $this->product->prefix->prefix_code ?? 'mama-'; // استخدم البريفكس المنتج أو "mama-" إذا لم يكن موجودًا
        $optionValues = $this->optionValues;
        $sku = $prefixCode . str_pad($this->id, 3, '0', STR_PAD_LEFT); // تكوين SKU

        // حلقة لجلب كل القيم المرتبطة بالـ variant من الجدول الوسيط
        foreach ($optionValues as $optionValue) {
            // جلب اسم الخيار نفسه (من جدول options)
            $optionName = $optionValue->option->name;  // assuming لديك علاقة بين option_values و options
            $firstOptionNameLetter = substr($optionName, 0, 1);

            // جلب قيمة الـ option_value
            $optionValueText = substr($optionValue->value, 0, 3); // استخراج أول 3 حروف
            // إضافة اسم الخيار وقيمته إلى الـ SKU
            $sku .= '-' . $firstOptionNameLetter . '-' . $optionValueText;
        }

        return $sku;
    }

}
