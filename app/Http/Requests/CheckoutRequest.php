<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CheckoutRequest extends FormRequest
{
    /**
     * تحديد إذا كان المستخدم مخول لعمل هذا الطلب.
     *
     * @return bool
     */
    public function authorize()
    {
        return true; // يمكن تغييرها للتحقق من صلاحيات المستخدم إذا لزم الأمر.
    }

    /**
     * تحديد قواعد التحقق من صحة البيانات.
     *
     * @return array
     */
    public function rules()
    {
        return [
            // قواعد التحقق للطلب
            'full_name' => 'required|string|max:255',
            'phone'     => 'required|string|min:10|max:15',
            'address'   => 'required|string|max:255',
            'city'      => 'required|string|max:100',
            'state'     => 'required|string|max:100',

            // قواعد التحقق من بيانات المنتجات
            'products.*.id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',

            // كود الخصم
            'code' => 'nullable|string|exists:promo_codes,code',

            // إضافة قواعد أخرى حسب الحاجة...
        ];
    }

    /**
     * تخصيص الرسائل الخطأ.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'full_name.required' => 'الاسم الكامل مطلوب.',
            'phone.required'     => 'رقم الهاتف مطلوب.',
            'address.required'   => 'العنوان مطلوب.',
            'city.required'      => 'المدينة مطلوبة.',
            'state.required'     => 'الولاية مطلوبة.',

            'products.*.id.required' => 'رقم المنتج مطلوب.',
            'products.*.id.exists' => 'المنتج غير موجود.',
            'products.*.quantity.required' => 'كمية المنتج مطلوبة.',
            'products.*.quantity.min' => 'الكمية لا يمكن أن تكون أقل من 1.',

            'code.exists' => 'كود الخصم غير صالح أو منتهي الصلاحية.',
        ];
    }
}
