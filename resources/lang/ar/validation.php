<?php

return [

    /*
    |--------------------------------------------------------------------------
    | رسائل التحقق الافتراضية
    |--------------------------------------------------------------------------
    |
    | الأسطر التالية تحتوي على رسائل الخطأ الافتراضية المستخدمة من قبل
    | كلاس التحقق. بعض هذه القواعد تحتوي على عدة نسخ مثل قاعدة الحجم.
    | لا تتردد في تعديل أي من هذه الرسائل هنا.
    |
    */

    'accepted'             => 'يجب قبول :attribute.',
    'active_url'           => ':attribute ليس رابطًا صحيحًا.',
    'after'                => 'يجب أن يكون :attribute تاريخًا بعد :date.',
    'after_or_equal'       => 'يجب أن يكون :attribute تاريخًا بعد أو مساويًا لـ :date.',
    'alpha'                => 'يجب أن يحتوي :attribute على حروف فقط.',
    'alpha_dash'           => 'يجب أن يحتوي :attribute على حروف وأرقام وشرطات.',
    'alpha_num'            => 'يجب أن يحتوي :attribute على حروف وأرقام فقط.',
    'array'                => 'يجب أن يكون :attribute مصفوفة.',
    'before'               => 'يجب أن يكون :attribute تاريخًا قبل :date.',
    'before_or_equal'      => 'يجب أن يكون :attribute تاريخًا قبل أو مساويًا لـ :date.',
    'between'              => [
        'numeric' => 'يجب أن تكون قيمة :attribute بين :min و :max.',
        'file'    => 'يجب أن يكون حجم الملف :attribute بين :min و :max كيلوبايت.',
        'string'  => 'يجب أن يكون طول النص :attribute بين :min و :max حرفًا.',
        'array'   => 'يجب أن يحتوي :attribute على عناصر بين :min و :max.',
    ],
    'boolean'              => 'يجب أن تكون قيمة :attribute صحيحة أو خاطئة.',
    'confirmed'            => 'تأكيد :attribute غير مطابق.',
    'date'                 => ':attribute ليس تاريخًا صحيحًا.',
    'date_format'          => ':attribute لا يتوافق مع الشكل :format.',
    'different'            => 'يجب أن يكون :attribute و :other مختلفين.',
    'digits'               => 'يجب أن يحتوي :attribute على :digits رقمًا.',
    'digits_between'       => 'يجب أن يحتوي :attribute بين :min و :max رقمًا.',
    'dimensions'           => 'أبعاد صورة :attribute غير صحيحة.',
    'distinct'             => 'حقل :attribute يحتوي على قيمة مكررة.',
    'email'                => 'يجب أن يكون :attribute بريدًا إلكترونيًا صحيحًا.',
    'exists'               => 'الحقل :attribute المحدد غير صحيح.',
    'file'                 => 'يجب أن يكون :attribute ملفًا.',
    'filled'               => 'يجب ملء حقل :attribute.',
    'gt'                   => [
        'numeric' => 'يجب أن تكون قيمة :attribute أكبر من :value.',
        'file'    => 'يجب أن يكون حجم الملف :attribute أكبر من :value كيلوبايت.',
        'string'  => 'يجب أن يكون طول النص :attribute أكبر من :value حرفًا.',
        'array'   => 'يجب أن يحتوي :attribute على أكثر من :value عنصرًا.',
    ],
    'gte'                  => [
        'numeric' => 'يجب أن تكون قيمة :attribute أكبر من أو تساوي :value.',
        'file'    => 'يجب أن يكون حجم الملف :attribute أكبر من أو يساوي :value كيلوبايت.',
        'string'  => 'يجب أن يكون طول النص :attribute أكبر من أو يساوي :value حرفًا.',
        'array'   => 'يجب أن يحتوي :attribute على :value عنصر أو أكثر.',
    ],
    'image'                => 'يجب أن يكون :attribute صورة.',
    'in'                   => 'الحقل :attribute المحدد غير صحيح.',
    'in_array'             => 'الحقل :attribute غير موجود في :other.',
    'integer'              => 'يجب أن يكون :attribute عددًا صحيحًا.',
    'ip'                   => 'يجب أن يكون :attribute عنوان IP صحيحًا.',
    'ipv4'                 => 'يجب أن يكون :attribute عنوان IPv4 صحيحًا.',
    'ipv6'                 => 'يجب أن يكون :attribute عنوان IPv6 صحيحًا.',
    'json'                 => 'يجب أن يكون :attribute نص JSON صحيحًا.',
    'lt'                   => [
        'numeric' => 'يجب أن تكون قيمة :attribute أقل من :value.',
        'file'    => 'يجب أن يكون حجم الملف :attribute أقل من :value كيلوبايت.',
        'string'  => 'يجب أن يكون طول النص :attribute أقل من :value حرفًا.',
        'array'   => 'يجب أن يحتوي :attribute على أقل من :value عنصرًا.',
    ],
    'lte'                  => [
        'numeric' => 'يجب أن تكون قيمة :attribute أقل من أو تساوي :value.',
        'file'    => 'يجب أن يكون حجم الملف :attribute أقل من أو يساوي :value كيلوبايت.',
        'string'  => 'يجب أن يكون طول النص :attribute أقل من أو يساوي :value حرفًا.',
        'array'   => 'يجب أن يحتوي :attribute على :value عنصر أو أقل.',
    ],
    'max'                  => [
        'numeric' => 'قد لا تكون قيمة :attribute أكبر من :max.',
        'file'    => 'قد لا يكون حجم الملف :attribute أكبر من :max كيلوبايت.',
        'string'  => 'قد لا يكون طول النص :attribute أكبر من :max حرفًا.',
        'array'   => 'قد لا يحتوي :attribute على أكثر من :max عنصر.',
    ],
    'mimes'                => 'يجب أن يكون :attribute ملفًا من نوع: :values.',
    'mimetypes'            => 'يجب أن يكون :attribute ملفًا من نوع: :values.',
    'min'                  => [
        'numeric' => 'يجب أن تكون قيمة :attribute على الأقل :min.',
        'file'    => 'يجب أن يكون حجم الملف :attribute على الأقل :min كيلوبايت.',
        'string'  => 'يجب أن يكون طول النص :attribute على الأقل :min حرفًا.',
        'array'   => 'يجب أن يحتوي :attribute على الأقل :min عنصرًا.',
    ],
    'not_in'               => 'الحقل :attribute المحدد غير صحيح.',
    'not_regex'            => 'صيغة :attribute غير صحيحة.',
    'numeric'              => 'يجب أن يكون :attribute رقمًا.',
    'present'              => 'يجب تقديم حقل :attribute.',
    'regex'                => 'صيغة :attribute غير صحيحة.',
    'required'             => 'حقل :attribute مطلوب.',
    'required_if'          => 'حقل :attribute مطلوب عندما يكون :other هو :value.',
    'required_unless'      => 'حقل :attribute مطلوب ما لم يكن :other هو :values.',
    'required_with'        => 'حقل :attribute مطلوب عند توفر :values.',
    'required_with_all'    => 'حقل :attribute مطلوب عند توفر :values.',
    'required_without'     => 'حقل :attribute مطلوب عند عدم توفر :values.',
    'required_without_all' => 'حقل :attribute مطلوب عند عدم توفر أي من :values.',
    'same'                 => 'يجب أن يتطابق :attribute و :other.',
    'size'                 => [
        'numeric' => 'يجب أن تكون قيمة :attribute مساوية لـ :size.',
        'file'    => 'يجب أن يكون حجم الملف :attribute :size كيلوبايت.',
        'string'  => 'يجب أن يكون طول النص :attribute :size حرفًا.',
        'array'   => 'يجب أن يحتوي :attribute على :size عنصرًا.',
    ],
    'string'               => 'يجب أن يكون :attribute نصًا.',
    'timezone'             => 'يجب أن يكون :attribute منطقة زمنية صحيحة.',
    'unique'               => 'قيمة :attribute مُستخدمة من قبل.',
    'uploaded'             => 'فشل في تحميل :attribute.',
    'url'                  => 'صيغة الرابط :attribute غير صحيحة.',
    'uuid'                 => 'يجب أن يكون :attribute UUID صحيحًا.',

    /*
    |--------------------------------------------------------------------------
    | تخصيص أسماء الحقول
    |--------------------------------------------------------------------------
    |
    | هنا يمكنك تخصيص رسائل الأخطاء لكل حقل على حدة.
    |
    */

    'attributes' => [
        'province' => 'المحافظة',
        'shipping_cost' => 'تكلفة الشحن',
    ],

];
