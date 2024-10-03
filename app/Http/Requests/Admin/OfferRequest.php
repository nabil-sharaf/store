<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class OfferRequest extends FormRequest
{
    public function authorize()
    {
        return true; // يمكنك ضبط هذا حسب الحاجة
    }

    public function rules()
    {
        return [
            'offer_name' => 'required|string|max:255',
            'offer_quantity' => 'required|integer|min:1|gte:free_quantity',
            'free_quantity' => 'required|integer|min:0|lte:offer_quantity',
            'customer_type' => 'required|in:goomla,regular,all',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'product_id' => 'required|exists:products,id',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'اسم العرض مطلوب.',
            'name.string' => 'اسم العرض يجب أن يكون نصاً.',
            'name.max' => 'اسم العرض يجب أن لا يتجاوز 255 حرفاً.',
            'offer_quantity.required' => 'الكمية المطلوبة مطلوبة.',
            'offer_quantity.integer' => 'الكمية المطلوبة يجب أن تكون رقم.',
            'offer_quantity.min' => 'الكمية المطلوبة يجب أن تكون على الأقل 1.',
            'offer_quantity.gte' => 'الكمية المطلوبة يجب ان تكون اكبر من او تساوي الكمية المجانية.',
            'free_quantity.lte' => 'الكمية المجانية يجب ان تكون اصغر من او تساوي الكمية المطلوبة.',
            'free_quantity.required' => 'الكمية المجانية مطلوبة.',
            'free_quantity.integer' => 'الكمية المجانية يجب أن تكون رقم.',
            'free_quantity.min' => 'الكمية المجانية يجب أن تكون 0 أو أكثر.',
            'customer_type.required' => 'نوع العميل مطلوب.',
            'customer_type.in' => 'نوع العميل يجب أن يكون قطاعي أو جملة أو الكل.',
            'start_date.required' => 'تاريخ البداية مطلوب.',
            'start_date.date' => 'تاريخ البداية يجب أن يكون تاريخ صحيح.',
            'start_date.after_or_equal' => 'تاريخ البداية يجب أن يكون اليوم أو بعده.',
            'end_date.required' => 'تاريخ النهاية مطلوب.',
            'end_date.date' => 'تاريخ النهاية يجب أن يكون تاريخ صحيح.',
            'end_date.after_or_equal' => 'تاريخ النهاية يجب أن يكون بعد تاريخ البداية.',
            'product_id.required'=>'لابد من اختيار منتج لتطبيق العرض عليه',
            'product_id.exists'=>'لابد ان يكون المنتج موجود في  الستوك'
        ];
    }
}
