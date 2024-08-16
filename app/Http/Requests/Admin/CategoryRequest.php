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
        return [
            'name' => 'required|string|max:25|'.
                       Rule::unique('categories','name')->ignore($Id),
            'description' => 'nullable|string|max:255',
            'parent_id' => '|string|nullable|exists:categories,id'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'اسم الكاتيجوري مطلوب',
            'name.unique' => 'هذا الاسم موجود مسبقا',
            'name.max' => 'اسم الفئة يجب أن لا يتجاوز 25 حرفاً',
            'description.max' => 'اسم الفئة يجب أن لا يتجاوز 255 حرفاً',
            'parent_id.exists' => 'الكاتيجوري الأب المحددة غير موجودة'
        ];
    }
}