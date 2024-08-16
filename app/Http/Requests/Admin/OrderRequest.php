<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class OrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules()
    {
        return [
            'user_id' => 'required|exists:users,id',
            'products' => 'required|array|min:1',
            'products.*.id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
        ];
    }

    public function messages()
    {
        return [
            'user_id.required' => 'يرجى اختيار المستخدم',
            'user_id.exists' => 'المستخدم المحدد غير موجود',
            'products.required' => 'يرجى إضافة منتج واحد على الأقل',
            'products.*.id.required' => 'يرجى اختيار المنتج',
            'products.*.id.exists' => 'المنتج المحدد غير موجود',
            'products.*.quantity.required' => 'يرجى تحديد الكمية',
            'products.*.quantity.integer' => 'الكمية يجب أن تكون رقمًا صحيحًا',
            'products.*.quantity.min' => 'الكمية يجب أن تكون 1 على الأقل',
        ];
    }
}
