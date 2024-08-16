<?php

namespace App\Http\Requests\Admin;
use Illuminate\Contracts\Validation\Validator;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
   public function authorize()
    {
        return true; // يمكنك تعديل هذا وفقًا لمنطق التفويض الخاص بك
    }

    public function rules()
    {
        return [
            'name' => 'required|max:255',
            'description' => 'required',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'categories' => 'required|array',
            'categories.*' => 'exists:categories,id',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048'
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
            'price.min' => 'يجب أن يكون السعر 0 أو أكثر',
            'quantity.required' => 'الكمية مطلوبة',
            'quantity.integer' => 'يجب أن تكون الكمية عددًا صحيحًا',
            'quantity.min' => 'يجب أن تكون الكمية 0 أو أكثر',
            'categories.required' => 'يجب اختيار فئة واحدة على الأقل',
            'categories.array' => 'يجب أن تكون الفئات مصفوفة',
            'categories.*.exists' => 'الفئة المحددة غير موجودة',
            'images.*.image' => 'يجب أن يكون الملف صورة',
            'images.*.mimes' => 'يجب أن تكون الصورة من نوع: jpeg, png, jpg, gif',
            'images.*.max' => 'يجب أن لا يتجاوز حجم الصورة 2 ميجابايت',
        ];
    }
}
