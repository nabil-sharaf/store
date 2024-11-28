<?php

namespace App\Http\Requests\Admin;

use App\Models\Admin\Option;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class OptionRequest extends FormRequest
{
    /**
     * Prepare the data for validation.
     */
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'name' => [
                'ar' => trim($this->input('name.ar')),
                'en' => trim($this->input('name.en')),
            ],
            'values' => collect($this->input('values', []))->map(function ($value) {
                return [
                    'ar' => trim($value['ar']),
                    'en' => trim($value['en']),
                ];
            })->all(),
        ]);
    }


    public function rules(): array
    {
        return [
            'name' => 'required|array',
            'name.ar' => [
                'required',
                'string',
                function ($attribute, $value, $fail) {
                    $this->validateOptionName($value, 'ar', $fail);
                }
            ],
            'name.en' => [
                'required',
                'string',
                function ($attribute, $value, $fail) {
                    $this->validateOptionName($value, 'en', $fail);
                }],
            'values' => 'required|array|min:1',
            'values.*.ar' => 'required|string',
            'values.*.en' => 'required|string',
        ];
    }

    protected function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $values = $this->input('values');

            if (!is_array($values) || empty($values)) {
                $validator->errors()->add('values', 'يجب إضافة قيمة واحدة على الأقل للأوبشن.');
                return;
            }

            // تحويل القيم إلى أحرف صغيرة وإزالة المسافات للمقارنة
            $valuesAr = array_map(function ($value) {
                return Str::lower(trim($value));
            }, array_column($values, 'ar'));

            $valuesEn = array_map(function ($value) {
                return Str::lower(trim($value));
            }, array_column($values, 'en'));

            // تحقق من تكرار القيم العربية
            if (count($valuesAr) !== count(array_unique($valuesAr))) {
                $validator->errors()->add('values', 'القيم (عربي) يجب أن تكون فريدة داخل الأوبشن.');
            }

            // تحقق من تكرار القيم الإنجليزية
            if (count($valuesEn) !== count(array_unique($valuesEn))) {
                $validator->errors()->add('values', 'القيم (إنجليزي) يجب أن تكون فريدة داخل الأوبشن.');
            }
        });
    }

    protected function validateOptionName($value, $language, $fail)
    {
        $query = Option::where(function ($query) use ($value, $language) {
            $query->whereRaw("LOWER(TRIM(JSON_UNQUOTE(JSON_EXTRACT(name, \"$.{$language}\")))) = ?", [Str::lower(trim($value))]);
        });

        // استثناء الـ ID الحالي في حالة التعديل
        $optionId = $this->route('option');
        if ($optionId) {
            if (is_object($optionId)) {
                $query->where('id', '!=', $optionId->id);
            } else {
                $query->where('id', '!=', (int)$optionId);
            }
        }

        if ($query->exists()) {
            if ($language == 'ar') {
                $colomun = 'العربي';
            } else {
                $colomun = 'بالانجليزية';
            }
            $fail("اسم الأوبشن ({$colomun}) موجود بالفعل.");
        }
    }


    public function messages(): array
    {
        return [
            'name.ar.unique' => 'اسم الأوبشن (عربي) موجود بالفعل.',
            'name.en.unique' => 'اسم الأوبشن (إنجليزي) موجود بالفعل.',
            'values.required' => 'يجب إضافة قيمة واحدة على الأقل للأوبشن.',
            'values.array' => 'صيغة القيم غير صحيحة.',
            'values.min' => 'يجب إضافة قيمة واحدة على الأقل للأوبشن.',
            'values.*.ar.required' => 'القيمة (عربي) مطلوبة.',
            'values.*.en.required' => 'القيمة (إنجليزي) مطلوبة.',
        ];
    }
}
