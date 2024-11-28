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

    public function prepareForValidation()
    {
        if ($this->has('variants')) {
            // تنظيف مصفوفة variants من العناصر الفارغة وإعادة ترتيب المؤشرات
            $variants = array_values(array_filter($this->variants, function ($variant) {
                return !empty($variant) && isset($variant['values']);
            }));

            $this->merge(['variants' => $variants]);
        }
    }

    public function rules()
    {
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

        if ($this->has('variants') && !empty($this->variants)) {
            $rules['variants'] = ['array', 'min:1'];
            $rules['variants.*.options'] = ['required', 'array', 'min:1'];
            $rules['variants.*.values'] = ['required', 'array', 'min:1'];
            $rules['variants.*.options.*'] = ['required', 'exists:options,id'];
            $rules['variants.*.values.*'] = ['required', 'exists:option_values,id'];
            $rules['variants.*.price'] = ['required', 'numeric', 'min:0'];
            $rules['variants.*.goomla_price'] = ['required', 'numeric', 'min:0'];
            $rules['variants.*.quantity'] = ['required', 'integer', 'min:1'];
            $rules['variants.*.images'] = ['nullable', 'array'];
            $rules['variants.*.images.*'] = ['image', 'max:2048'];

            $rules['variants'][] = function ($attribute, $variants, $fail) {
                $this->validateVariants($variants, $fail);
            };
        }

        return $rules;
    }

    protected function validateVariants($variants, $fail)
    {
        if (empty($variants)) {
            return;
        }

        // Check if first variant exists and has options
        if (!isset($variants[0]) || !isset($variants[0]['options']) || !is_array($variants[0]['options'])) {
            $fail("التنوع الأول يجب أن يحتوي على خصائص صحيحة");
            return;
        }

        // تجميع كل مجموعات القيم في الفاريانت
        $combinations = [];

        // الحصول على الأوبشنز من أول فاريانت
        $firstVariantOptions = collect($variants[0]['options'] ?? [])->sort()->values()->toArray();

        foreach ($variants as $variantIndex => $variant) {
            // Validate variant structure
            if (!isset($variant['options']) || !is_array($variant['options'])) {
                $fail("التنوع رقم " . ($variantIndex + 1) . " يجب أن يحتوي على خصائص");
                continue;
            }

            if (!isset($variant['values']) || !is_array($variant['values'])) {
                $fail("التنوع رقم " . ($variantIndex + 1) . " يجب أن يحتوي على قيم");
                continue;
            }

            // التحقق من أن الأوبشنز متطابقة في كل الفاريانت
            $currentVariantOptions = collect($variant['options'])->sort()->values()->toArray();

            if ($currentVariantOptions !== $firstVariantOptions) {
                $fail("يجب أن تكون جميع التنوعات تحتوي على نفس الخصائص بالضبط. التنوع رقم " . ($variantIndex + 1) . " يحتوي على خصائص مختلفة");
                continue;
            }

            // التحقق من تكرار الأوبشن في نفس الفاريانت
            $optionIds = [];
            foreach ($variant['values'] as $valueId) {
                $optionValue = DB::table('option_values')
                    ->select('option_id')
                    ->where('id', $valueId)
                    ->first();

                if (!$optionValue) {
                    $fail("قيمة غير صحيحة في التنوع رقم " . ($variantIndex + 1));
                    continue 2;
                }

                if (in_array($optionValue->option_id, $optionIds)) {
                    $fail("التنوع رقم " . ($variantIndex + 1) . " يحتوي على نفس الخاصية مكررة");
                    continue 2;
                }

                // التحقق من أن قيمة الأوبشن تنتمي لأحد الأوبشنز المحددة
                if (!in_array($optionValue->option_id, $firstVariantOptions)) {
                    $fail("قيمة خاطئة في التنوع رقم " . ($variantIndex + 1) . ". يجب أن تكون جميع القيم تابعة للخصائص المحددة");
                    continue 2;
                }

                $optionIds[] = $optionValue->option_id;
            }

            // التحقق من تفرد مجموعة القيم
            $sortedValues = collect($variant['values'])->sort()->values()->implode(',');

            if (in_array($sortedValues, $combinations)) {
                $fail('لقد أدخلت تنوعين بنفس القيم من فضلك قم بالتعديل على أحدهم أولًا');
                continue;
            }

            $combinations[] = $sortedValues;
        }

        // التحقق من عدم التكرار مع الفاريانت الموجودة في قاعدة البيانات
        if ($this->route('product')) {
            $productId = $this->route('product');

            foreach ($variants as $variantIndex => $variant) {
                if (!isset($variant['values'])) {
                    continue;
                }

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
