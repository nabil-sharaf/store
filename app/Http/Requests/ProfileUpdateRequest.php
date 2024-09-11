<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:100'],
            'last_name' => ['required','string','max:100'],
            'email' => ['string', 'email', 'max:255', Rule::unique(User::class)->ignore($this->user()->id)],
            'phone' => ['required', 'string', 'regex:/^(01)[0-9]{9}$/','unique:users,phone,'.$this->user()->id], // تحقق من أن الجوال يبدأ بـ 01 ومكون من 11 رقمًا
            'current_password' => 'nullable|string|min:8',
            'new_password' => 'nullable|string|min:8|confirmed',
            ];
    }
    public function messages(): array
    {
        return [
            'name.required' => 'حقل الاسم الأول مطلوب.',
            'last_name.required' => 'حقل الاسم الاخير مطلوب',
            'phone.required' => 'حقل الموبايل مطلوب.',
            'phone.regex' => 'يجب أن يكون رقم الموبايل 11 رقم ويبدأ ب 01 =',
            'phone.unique' => 'رقم التليفون مسجل مسبقا',
            'email.unique' => 'البريد الالكتروني مسجل مسبقاً.',
            'new_password.min' => __('profile.new_password_min'),
            'new_password.confirmed' => __('profile.password_confirmation_mismatch'),
            'current_password.min' => __('profile.password_min'),
        ];
    }


    // تعديل هذه الدالة لتحديد اسم الأخطاء
//    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
//    {
//        // إعادة التوجيه مع الأخطاء باستخدام اسم المجموعة المحدد 'registerErrors'
//        throw new \Illuminate\Validation\ValidationException($validator, redirect()->back()->withErrors($validator, 'errors')->withInput());
//    }
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($this->filled('new_password') && !Hash::check($this->current_password, auth()->user()->password)) {
                $validator->errors()->add('current_password', __('profile.password_current_incorrect'));
            }
        });
    }


}
