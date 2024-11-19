<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class OptionRequest extends FormRequest
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
        return [
            'name' => 'required|array',
            'name.ar' => 'required|string',
            'name.en' => 'required|string',
            'values' => 'required|array',
            'values.*.ar' => 'required|string',
            'values.*.en' => 'required|string',
        ];
    }

    /**
     * Configure the validator instance.
     */
    protected function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $valuesAr = array_column($this->input('values'), 'ar');
            $valuesEn = array_column($this->input('values'), 'en');

            // تحقق من تكرار القيم العربية
            if (count($valuesAr) !== count(array_unique($valuesAr))) {
                $validator->errors()->add('values.*.ar', 'القيم (عربي) يجب أن تكون فريدة داخل الأوبشن.');
            }

            // تحقق من تكرار القيم الإنجليزية
            if (count($valuesEn) !== count(array_unique($valuesEn))) {
                $validator->errors()->add('values.*.en', 'القيم (إنجليزي) يجب أن تكون فريدة داخل الأوبشن.');
            }
        });
    }    public function messages(): array
    {
        return [
            'values.*.ar.unique' => 'القيمة (عربي) موجودة بالفعل لهذا .',
            'values.*.en.unique' => 'القيمة (إنجليزي) موجودة بالفعل لهذا الأوبشن.',
            'option_id.required' => 'يجب تحديد الأوبشن.',
            'option_id.exists' => 'الأوبشن المحدد غير موجود.',
            'values.*.ar.required' => 'القيمة (عربي) مطلوبة.',
            'values.*.en.required' => 'القيمة (إنجليزي) مطلوبة.',

        ];
    }
}
