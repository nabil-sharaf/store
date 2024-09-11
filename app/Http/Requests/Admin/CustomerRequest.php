<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class CustomerRequest extends FormRequest
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
    public function rules(): array
    {
        $rules = [];

        if ($this->isUpdatingUser()) {
            // قواعد للتحقق في حالة تعديل بيانات المستخدم
            $rules['name'] = 'required|string|max:255';
            $rules['customer_type'] = 'required|string|in:goomla,regular';
            $rules['status'] = 'required|numeric|in:0,1';

        }

        if ($this->isVipRequest()) {
            // قواعد للتحقق في حالة جعل المستخدم VIP
            $rules['start_date'] = 'required|date';
            $rules['end_date'] = 'required|date|after_or_equal:start_date|after_or_equal:today';
            $rules['discount'] = 'required|numeric|gt:0';
        }

        return $rules;
    }

    /**
     * تحديد ما إذا كان الطلب هو لتعديل بيانات المستخدم.
     */
    protected function isUpdatingUser(): bool
    {
        // تحديد ما إذا كان الطلب هو لتعديل المستخدم بناءً على مدخل معين
        // يمكن استخدام اسم الفورم أو زر الإرسال أو قيمة مخفية
        return $this->has('update_user');
    }

    /**
     * تحديد ما إذا كان الطلب هو لجعل المستخدم VIP.
     */
    protected function isVipRequest(): bool
    {
        // تحديد ما إذا كان الطلب هو لجعل المستخدم VIP بناءً على مدخل معين
        // يمكن استخدام اسم الفورم أو زر الإرسال أو قيمة مخفية
        return $this->has('make_vip');
    }
}
