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
            'name' => 'nullable|string|max:255|required_without:description',
            'description' => 'nullable|string|required_without:name',
            'prefix_code' => 'required|string|unique:prefixes,prefix_code,' . $this->prefix?->id,
        ];
    }

    public function messages(): array
    {
        return [
            'name.required_without' => 'يجب ادخال الوصف بالعربية او الانجليزية على الأقل.',
            'description.required_without' => 'يجب ادخال الوصف بالعربية او الانجليزية على الأقل.',
            'name.string' => 'يجب أن يكون الاسم نصاً.',
            'description.string' => 'يجب أن يكون الوصف نصاً.',
            'name.max' => 'يجب ألا يتجاوز الوصف 255 حرفًا.',
            'prefix_code.required' => 'حقل الكود  مطلوب.',
            'prefix_code.unique' => 'الكود  مستخدم بالفعل.',
        ];
    }

}


