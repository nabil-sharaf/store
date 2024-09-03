<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email'=>['nullable','unique:users,email'],
            'phone' => ['required','unique:users,phone', 'string', 'regex:/^(01)[0-9]{9}$/'], // تحقق من أن الجوال يبدأ بـ 01 ومكون من 11 رقمًا
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ];
    }

    /**
     * Get the validation messages that apply to the request.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'name.required' => 'حقل الاسم مطلوب.',
            'phone.required' => 'حقل الموبايل مطلوب.',
            'phone.regex' => 'يجب أن يكون رقم الموبايل 11 رقم ويبدأ ب 01 =',
            'phone.unique' => 'هذا الرقم مسجل مسبقاً.',
            'email.unique' => 'البريد الالكتروني مسجل مسبقاً.',
            'password.required' => 'حقل كلمة المرور مطلوب.',
            'password.min' => 'يجب أن تكون كلمة المرور على الأقل 8 أحرف.',
            'password.confirmed' => 'تأكيد كلمة المرور لا يتطابق.',
        ];
    }


    // تعديل هذه الدالة لتحديد اسم الأخطاء
    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        // إعادة التوجيه مع الأخطاء باستخدام اسم المجموعة المحدد 'registerErrors'
        throw new \Illuminate\Validation\ValidationException($validator, redirect()->route('register')->withErrors($validator, 'registerErrors')->withInput());
    }
}
