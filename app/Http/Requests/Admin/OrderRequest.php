<?php

namespace App\Http\Requests\Admin;

use App\Models\Admin\Product;
use App\Models\Admin\Variant;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

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
        // تعريف قاعدة التحقق المخصصة للفاريانت
        Validator::extend('variant_required', function ($attribute, $value, $parameters, $validator) {
            // استخراج رقم المنتج من اسم الحقل
            preg_match('/products\.(\d+)\.variant_id/', $attribute, $matches);
            if (!isset($matches[1])) {
                return false;
            }

            $index = $matches[1];
            $data = $validator->getData();

            // التأكد من وجود المنتج في الطلب
            if (!isset($data['products'][$index]['id'])) {
                return false;
            }

            // جلب المنتج وفحص الفاريانتات
            $productId = $data['products'][$index]['id'];
            $product = Product::with('variants')->find($productId);

            if (!$product) {
                return false;
            }

            // إذا كان المنتج لديه فاريانتات، يجب اختيار فاريانت
            if ($product->variants->isNotEmpty()) {
                return !is_null($value) && $value !== '';
            }

            // إذا لم يكن لديه فاريانتات، يجب أن يكون الفاريانت فارغاً
            return is_null($value) || $value === '';
        });

        return [
            'user_id' => 'nullable|exists:users,id',
            'products' => 'required|array|min:1',
            'products.*.id' => 'required|exists:products,id',
            'products.*.quantity' => [
                'required',
                'integer',
                'min:1',
                function ($attribute, $value, $fail) {
                    preg_match('/products\.(\d+)\.quantity/', $attribute, $matches);
                    $index = $matches[1];
                    $productId = request()->input("products.{$index}.id");

                    if ($productId) {
                        $product = Product::find($productId);
                        if ($product && $value > $product->quantity) {
                            $fail('الكمية المطلوبة غير متوفرة في المخزون.');
                        }
                    }
                }
            ],
            'products.*.variant_id' => [
                'variant_required',
                function ($attribute, $value, $fail) {
                    if (!empty($value)) {
                        // التحقق من أن الفاريانت ينتمي للمنتج الصحيح
                        preg_match('/products\.(\d+)\.variant_id/', $attribute, $matches);
                        $index = $matches[1];
                        $productId = request()->input("products.{$index}.id");

                        if ($productId) {
                            $variantExists = Variant::where('id', $value)
                                ->where('product_id', $productId)
                                ->exists();

                            if (!$variantExists) {
                                $fail('الفاريانت المحدد غير صحيح لهذا المنتج.');
                            }
                        }
                    }
                }
            ],
            'full_name' => 'required|string|max:255',
            'phone' => 'required|numeric|digits:11',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
        ];
    }

    public function messages()
    {
        return [
            'user_id.exists' => 'المستخدم المحدد غير موجود',
            'products.required' => 'يرجى إضافة منتج واحد على الأقل',
            'products.*.id.required' => 'يرجى اختيار المنتج',
            'products.*.id.exists' => 'المنتج المحدد غير موجود',
            'products.*.quantity.required' => 'يرجى تحديد الكمية',
            'products.*.quantity.integer' => 'الكمية يجب أن تكون رقمًا صحيحًا',
            'products.*.quantity.min' => 'الكمية يجب أن تكون 1 على الأقل',

            'full_name.required' => 'الاسم  مطلوب.',
            'full_name.string' => 'الاسم  يجب أن يكون نص.',
            'full_name.max' => 'اسمك الكامل يجب أن لا يتجاوز 255 حرفًا.',

            'phone.required' => 'رقم الهاتف مطلوب.',
            'phone.numeric' => 'رقم الهاتف يجب أن يكون أرقام فقط.',
            'phone.digits' => 'رقم الهاتف يجب أن يتكون من 11 رقمًا.',

            'address.required' => 'العنوان مطلوب.',
            'address.string' => 'العنوان يجب أن يكون نص.',
            'address.max' => 'العنوان يجب أن لا يتجاوز 255 حرفًا.',

            'city.required' => 'اسم المدينة مطلوب .',
            'city.string' => 'اسم المدينة يجب أن تكون نص.',
            'city.max' => 'اسم المدينة يجب أن لا تتجاوز 100 حرف.',

            'state.required' => 'اسم المحافظة مطلوب.',
            'state.string' => 'اسم المحافظة يجب أن تكون نص.',
            'state.max' => 'اسم المحافظة يجب أن لا تتجاوز 100 حرف.',
            'products.*.variant_id.variant_required' => 'يجب اختيار فاريانت للمنتج',

        ];
    }
}
