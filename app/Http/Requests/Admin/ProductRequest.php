<?php

namespace App\Http\Requests\Admin;

use App\Models\Admin\Variant;
use Illuminate\Contracts\Validation\Validator;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class ProductRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
//        $id = $this->route('product') ? $this->route('product')->id : null;

        // الحصول على ID المنتج من كائن المنتج في الراوت
        $id = $this->route('product') ?? null;

        $rules = [
            'name' => 'required|max:255|' . Rule::unique('products', 'name')->ignore($id),
            'description' => 'required',
            'info' => 'required',
            'price' => 'required|numeric|gt:0',
            'goomla_price' => 'required|numeric|gt:0',
            'quantity' => 'required|integer|min:0',
            'discount_type' => 'nullable|in:fixed,percentage',
            'discount' => 'nullable|required_if:discount_type,fixed,percentage|numeric|gt:0',
            'start_date' => 'nullable|required_if:discount_type,fixed,percentage|date',
            'end_date' => 'nullable|required_if:discount_type,fixed,percentage|date|after_or_equal:start_date',
            'categories' => 'required|array',
            'categories.*' => 'exists:categories,id',
            'images.*' => 'image|max:2048',
            'prefix_id' => 'nullable|exists:prefixes,id',
        ];

        if ($this->has('variants')) {
            $rules['variants.*.options'] = ['required', 'array', 'min:1'];  // min:1 للتأكد إن المصفوفة مش فاضية
            $rules['variants.*.values'] = ['required', 'array', 'min:1'];  // min:1 للتأكد إن المصفوفة مش فاضية
            $rules['variants.*.options.*'] = ['required', 'exists:options,id'];
            $rules['variants.*.values.*'] = ['required', 'exists:option_values,id'];  // للتأكد إن القيم موجودة في جدول option_values
            $rules['variants.*.price'] = ['required', 'numeric', 'min:0'];
            $rules['variants.*.goomla_price'] = ['required', 'numeric', 'min:0'];
            $rules['variants.*.quantity'] = ['required', 'integer', 'min:1'];
            $rules['variants.*.images'] = ['nullable', 'array'];
            $rules['variants.*.images.*'] = ['image', 'max:2048'];
//            $rules['variants.*.id'] = ['nullable,exists:variants,id']; // للتحقق من وجود الفاريانت إذا كان تعديل


            // التحقق من تفرد مجموعات القيم في الفاريانت
            $rules['variants'] = [
                'array',
                function ($attribute, $variants, $fail) {
                    $this->validateUniqueVariantCombinations($variants, $fail);
                }
            ];
        }

        return $rules;
    }

    protected function validateUniqueVariantCombinations($variants, $fail)
    {
        // تجميع كل مجموعات القيم في الفاريانت
        $combinations = [];

        foreach ($variants as $variantIndex => $variant) {
            if (!isset($variant['values']) || !is_array($variant['values'])) {
                continue;
            }
            // التحقق من تكرار الأوبشن في نفس الفاريانت
            $optionIds = [];
            foreach ($variant['values'] as $valueId) {
                // جلب option_id الخاص بهذه القيمة
                $optionValue = DB::table('option_values')
                    ->select('option_id')
                    ->where('id', $valueId)
                    ->first();

                if (!$optionValue) {
                    continue;
                }

                if (in_array($optionValue->option_id, $optionIds)) {
                    $fail("الفاريانت رقم " . ($variantIndex + 1) . " يحتوي على نفس الخاصية مكررة");
                    return;
                }

                $optionIds[] = $optionValue->option_id;
            }

            // التحقق من تفرد مجموعة القيم بين الفاريانت
            $sortedValues = collect($variant['values'])->sort()->values()->implode(',');

            if (in_array($sortedValues, $combinations)) {
                $fail('لقد أدخلت تنوعين بنفس القيم من فضلك قم بالتعديل على أحدهم أولًا');
                return;
            }

            $combinations[] = $sortedValues;
        }


// التحقق من عدم التكرار مع الفاريانت الموجودة في قاعدة البيانات
        if ($this->route('product')) {
            $productId = $this->route('product');

            foreach ($variants as $variantIndex => $variant) {
                $variantId = $variant['id'] ?? null;

                $existingVariants = Variant::where('product_id', $productId)
                    ->when($variantId, fn($query) => $query->where('id', '!=', $variantId))
                    ->with('optionValues')
                    ->get();

                $currentCombination = collect($variant['values'])->sort()->values()->implode(',');

                foreach ($existingVariants as $existingVariant) {
                    $existingCombination = $existingVariant->optionValues->pluck('id')
                        ->sort()
                        ->values()
                        ->implode(',');

                    if ($currentCombination === $existingCombination) {
                        $fail('هذا التنوع موجود بالفعل لنفس المنتج');
                        return;
                    }
                }
            }
        }

    }

    public function messages()
    {
        return [
            // ... باقي رسائل الخطأ الحالية ...
            'variants.*.values.required' => 'قيم حقل الأوبشن مطلوبة',
            'variants.*.values.array' => 'قيم الفاريانت يجب أن تكون مصفوفة',
            'variants.*.values.min' => 'يجب إضافة قيمة واحدة على الأقل للاوبشن',
            'variants.*.values.*.required' => 'قيمة الأوبشن مطلوبة',
            'variants.*.values.*.exists' => 'قيمة الأوبشن غير موجودة',
            'variants.*.option.min' => 'يجب إضافة اوبشن واحد على الأقل',
            'variants.*.options.*.required' => 'لا يمكن ترك حقل الأوبشن فارغ ',
            'variants.*.price.required' => 'سعر الفاريانت مطلوب',
            'variants.*.price.numeric' => 'يجب أن يكون سعر الفاريانت رقماً',
            'variants.*.price.min' => 'يجب أن يكون سعر الفاريانت 0 أو أكثر',
            'variants.*.goomla_price.required' => 'سعر الجملة للفاريانت مطلوب',
            'variants.*.goomla_price.numeric' => 'يجب أن يكون سعر الجملة للفاريانت رقماً',
            'variants.*.goomla_price.min' => 'يجب أن يكون سعر الجملة للفاريانت 0 أو أكثر',
            'variants.*.quantity.required' => 'كمية الفاريانت مطلوبة',
            'variants.*.quantity.integer' => 'يجب أن تكون كمية الفاريانت عدداً صحيحاً',
            'variants.*.quantity.min' => 'يجب أن تكون كمية الفاريانت 1 أو أكثر',

        ];
    }
}
