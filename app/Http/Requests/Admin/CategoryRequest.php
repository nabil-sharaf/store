<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CategoryRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $Id = $this->route('category') ? $this->route('category')->id : null;
        $rules =  [
            'name' => 'required|string|max:25|'.
                       Rule::unique('categories','name')->ignore($Id),
            'description' => 'required|string|max:255',
            'parent_id' => '|string|nullable|exists:categories,id',
        ];

        // إذا كان الطلب POST (إضافة)، نطلب الصورة، أما إذا كان التحديث فلا نجعلها مطلوبة
        if ($this->isMethod('post')) {
            $rules['image'] = 'required|image|max:2048';
        } else {
            $rules['image'] = 'nullable|image|max:2048';
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'name.required' => 'اسم الكاتيجوري مطلوب',
            'name.unique' => 'هذا الاسم موجود مسبقا',
            'name.max' => 'اسم الفئة يجب أن لا يتجاوز 25 حرفاً',
            'description.max' => 'اسم الفئة يجب أن لا يتجاوز 255 حرفاً',
            'description.required' => 'اضف وصف للقسم',
            'parent_id.exists' => 'الكاتيجوري الأب المحددة غير موجودة',
            'image.required' => 'يرجى رفع صورة للقسم.',
            'image.image' => 'يجب أن يكون الملف المرفوع صورة.',
            'image.mimes' => 'يجب أن تكون الصورة من نوع: jpeg, png, jpg, gif.',
            'image.max' => 'يجب ألا يزيد حجم الصورة عن 2 ميجابايت.',
        ];
    }
}
