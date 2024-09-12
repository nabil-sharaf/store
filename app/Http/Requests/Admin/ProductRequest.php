<?php

namespace App\Http\Requests\Admin;
use Illuminate\Contracts\Validation\Validator;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductRequest extends FormRequest
{
   public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $id = $this->route('product') ? $this->route('product')->id : null;

        return [
            'name' => 'required|max:255|'. Rule::unique('products','name')->ignore($id),
            'description' => 'required',
            'price' => 'required|numeric|gt:0',
            'goomla_price' => 'required|numeric|gt:0',
            'quantity' => 'required|integer|min:0',
            'discount_type' => 'nullable|in:fixed,percentage',
            'discount' => 'nullable|required_if:discount_type,fixed,percentage|numeric|gt:0',
            'start_date' => 'nullable|required_if:discount_type,fixed,percentage|date',
            'end_date' => 'nullable|required_if:discount_type,fixed,percentage|date|after_or_equal:start_date',
            'categories' => 'required|array',
            'categories.*' => 'exists:categories,id',
            'images.*' => 'image|max:2048'
        ];
    }



    public function messages()
    {
        return [
            'name.required' => 'اسم المنتج مطلوب',
            'name.unique' => 'اسم المنتج موجود مسبقا',
            'name.max' => 'اسم المنتج يجب أن لا يتجاوز 255 حرفًا',
            'description.required' => 'وصف المنتج مطلوب',
            'price.required' => 'سعر المنتج مطلوب',
            'price.numeric' => 'يجب أن يكون السعر رقمًا',
            'price.gt' => 'يجب أن يكون السعر أكبر من الصفر',
            'goomla_price.required' => 'سعر المنتج الجملة مطلوب',
            'goomla_price.numeric' => 'يجب أن يكون سعر الجملة رقمًا',
            'goomla_price.gt' => 'يجب أن يكون سعر الجملة أكبر من الصفر',
            'quantity.required' => 'الكمية مطلوبة',
            'quantity.integer' => 'يجب أن تكون الكمية عددًا صحيحًا',
            'quantity.min' => 'يجب أن تكون الكمية 0 أو أكثر',
            'discount_type.in' => 'نوع الخصم يجب أن يكون نسبة أو ثابت',
            'discount.required_if' => 'قيمة الخصم مطلوبة عند اختيار نوع الخصم',
            'discount.numeric' => 'يجب أن تكون قيمة الخصم رقمًا',
            'discount.gt' => 'يجب أن تكون قيمة الخصم أكبر من الصفر',
            'start_date.required_if' => 'تاريخ بداية الخصم مطلوب عند تحديد الخصم',
            'start_date.date' => 'يجب أن يكون تاريخ بداية الخصم تاريخًا صحيحًا',
            'end_date.required_if' => 'تاريخ نهاية الخصم مطلوب',
            'end_date.date' => 'يجب أن يكون تاريخ نهاية الخصم تاريخًا صحيحًا',
            'end_date.after_or_equal' => 'يجب أن يكون تاريخ نهاية الخصم مساويًا أو بعد تاريخ البداية',
            'categories.required' => 'يجب اختيار فئة واحدة على الأقل',
            'categories.array' => 'يجب أن تكون الفئات مصفوفة',
            'categories.*.exists' => 'الفئة المحددة غير موجودة',
            'images.*.image' => 'يجب أن يكون الملف صورة',
            'images.*.max' => 'يجب أن لا يتجاوز حجم الصورة 2 ميجابايت'
        ];
    }
}
