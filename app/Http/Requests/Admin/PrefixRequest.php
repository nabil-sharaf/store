<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class PrefixRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name_ar' => 'nullable|string|max:255|required_without:name_en',
            'name_en' => 'nullable|string|max:255|required_without:name_ar',
            'prefix_code' => 'required|string|unique:prefixes,prefix_code,' . $this->prefix?->id,
        ];
    }

    public function messages(): array
    {
        return [
            'name_ar.required_without' => 'يجب إدخال الاسم بالعربية أو الإنجليزية على الأقل.',
            'name_en.required_without' => 'يجب إدخال الاسم بالعربية أو الإنجليزية على الأقل.',
            'name_ar.string' => 'يجب أن يكون الاسم بالعربية نصًا.',
            'name_en.string' => 'يجب أن يكون الاسم بالإنجليزية نصًا.',
            'name_ar.max' => 'يجب ألا يتجاوز الاسم بالعربية 255 حرفًا.',
            'name_en.max' => 'يجب ألا يتجاوز الاسم بالإنجليزية 255 حرفًا.',
            'prefix_code.required' => 'حقل الكود مطلوب.',
            'prefix_code.unique' => 'الكود مستخدم بالفعل.',
        ];
    }
}

