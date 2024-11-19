@extends('admin.layouts.app')

@section('page-title')
    المنتجات
@endsection

@section('content')
    <div class="card card-info">
        <div class="card-header">
            <h3 class="card-title float-left">إضافة منتج جديد</h3>
        </div>
        <!-- /.card-header -->
        <!-- form start -->
        <form id="product-form" class="form-horizontal" enctype="multipart/form-data" dir="rtl">
            @csrf
            <div class="card-body">
                <!-- اسم المنتج -->
                <div class="form-group row">
                    <label for="inputName" class="col-sm-2 control-label">اسم المنتج</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="inputName"
                               placeholder="أدخل اسم المنتج" name='name' value="{{ old('name') }}">
                        @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <!-- الوصف -->
                <div class="form-group row mt-4">
                    <label for="inputDescription" class="col-sm-2 control-label">وصف المنتج</label>
                    <div class="col-sm-10">
                        <textarea class="form-control @error('description') is-invalid @enderror" id="inputDescription"
                                  rows="3" placeholder="أدخل وصف المنتج"
                                  name='description'>{{ old('description') }}</textarea>
                        @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <!-- معلومات المنتج -->
                <div class="form-group row mt-4">
                    <label for="inputInfo" class="col-sm-2 control-label">معلومات المنتج</label>
                    <div class="col-sm-10">
                        <textarea class="form-control @error('info') is-invalid @enderror" id="inputInfo" rows="3"
                                  placeholder="أدخل معلومات وتفاصيل اضافية للمنتج"
                                  name='info'>{{ old('info') }}</textarea>
                        @error('info')
                        <div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <!-- السعر -->
                <div class="form-group row mt-4">
                    <label for="inputPrice" class="col-sm-2 control-label">سعر القطاعي</label>
                    <div class="col-sm-10">
                        <input type="number" step="0.01" class="form-control @error('price') is-invalid @enderror"
                               id="inputPrice" placeholder="أدخل سعر المنتج" name='price' value="{{ old('price') }}">
                        @error('price')
                        <div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                <!-- سعر الجملة -->
                <div class="form-group row mt-4">
                    <label for="inputGoomlaPrice" class="col-sm-2 control-label">سعر الجملة</label>
                    <div class="col-sm-10">
                        <input type="number" step="0.01"
                               class="form-control @error('goomla_price') is-invalid @enderror" id="inputGoomlaPrice"
                               placeholder="أدخل سعر الجملة للمنتج" name='goomla_price'
                               value="{{ old('goomla_price') }}">
                        @error('goomla_price')
                        <div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <!-- الكمية -->
                <div class="form-group row mt-4">
                    <label for="inputQuantity" class="col-sm-2 control-label">الكمية</label>
                    <div class="col-sm-10">
                        <input type="number" class="form-control @error('quantity') is-invalid @enderror"
                               id="inputQuantity" placeholder="أدخل كمية المنتج" name='quantity'
                               value="{{ old('quantity') }}" min="1" required>
                        @error('quantity')
                        <div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <!-- الفئات -->
                <div class="form-group row mt-4">
                    <label for="inputCategories" class="col-sm-2 control-label">الفئات</label>
                    <div class="col-sm-10">
                        <select multiple class="form-control select2 @error('categories') is-invalid @enderror"
                                id="inputCategories" name="categories[]" style="width: 100%">
                            @foreach($categories as $category)
                                <option
                                    value="{{ $category->id }}" {{ (is_array(old('categories')) && in_array($category->id, old('categories'))) ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('categories')
                        <div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                {{--البريفكس--}}
                <div class="form-group row mt-4">
                    <label for="prefix" class="col-sm-2 control-label"> البريفكس </label>
                    <div class="col-sm-10">
                        <select class="form-control select2  @error('prefix_id') is-invalid @enderror" id="prefix"
                                name="prefix_id" style="width: 100%">
                            <option value="">Mama</option>
                            @foreach($prefixes as $prefix)
                                <option
                                    value="{{ $prefix->id }}" {{ old('prefix_id' )}}>
                                    {{ $prefix->getTranslation('name','ar') }}
                                </option>
                            @endforeach
                        </select>
                        @error('prefix_id')
                        <div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <!-- نوع الخصم -->
                <div class="form-group row mt-4">
                    <label for="inputDiscountType" class="col-sm-2 control-label">نوع الخصم</label>
                    <div class="col-sm-10">
                        <select class="select2 form-control @error('discount_type') is-invalid @enderror"
                                id="inputDiscountType" name="discount_type">
                            <option value="" {{ old('discount_type') == '' ? 'selected' : '' }}>لا يوجد</option>
                            <option value="fixed" {{ old('discount_type') == 'fixed' ? 'selected' : '' }}>ثابت</option>
                            <option value="percentage" {{ old('discount_type') == 'percentage' ? 'selected' : '' }}>نسبة
                                مئوية
                            </option>
                        </select>
                        @error('discount_type')
                        <div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <!-- قيمة الخصم -->
                <div class="form-group row mt-4 discount-fields" style="display: none;">
                    <label for="inputDiscount" class="col-sm-2 control-label">قيمة الخصم</label>
                    <div class="col-sm-10">
                        <input type="number" step="0.01" class="form-control @error('discount') is-invalid @enderror"
                               id="inputDiscount" placeholder="أدخل قيمة الخصم" name='discount'
                               value="{{ old('discount',0)}}" min="0">
                        @error('discount')
                        <div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <!-- تاريخ البدء -->
                <div class="form-group row mt-4 discount-fields" style="display: none" ;>
                    <label for="inputStartDate" class="col-sm-2 control-label ">تاريخ البدء</label>
                    <div class="col-sm-10">
                        <input type="date" class="form-control @error('start_date') is-invalid @enderror"
                               id="inputStartDate" name='start_date' value="{{ old('start_date','') }}">
                        @error('start_date')
                        <div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <!-- تاريخ الانتهاء -->
                <div class="form-group row mt-4 discount-fields" style="display: none;">
                    <label for="inputEndDate" class="col-sm-2 control-label ">تاريخ الانتهاء</label>
                    <div class="col-sm-10">
                        <input type="date" class="form-control @error('end_date') is-invalid @enderror"
                               id="inputEndDate" name='end_date' value="{{ old('end_date','') }}">
                        @error('end_date')
                        <div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <!-- الصور -->
                <div class="form-group row mt-4 product-images-div">
                    <label for="inputImages" class="col-sm-2 control-label">صور المنتج</label>
                    <div class="col-sm-10">
                        <input type="file" class="form-control-file @error('images') is-invalid @enderror"
                               id="inputImages" name="images[]" multiple accept="image/*">
                        @error('images')
                        <div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <!-- قسم لمعاينة الصور -->
                <div class="form-group row  product-image-preview">
                    <div class="col-sm-2"></div>
                    <div class="col-sm-10">
                        <div id="imagePreviewContainer" class="d-flex flex-wrap"></div>
                    </div>
                </div>

                <div class="container mt-4">

                    <!-- الحاوية التي سيتم إضافة الفاريانتات إليها -->
                    <div id="variantContainer">
                        <!-- سيتم إضافة الفاريانتات هنا بواسطة الجافاسكريبت -->


                    </div>

                    <!-- زر إضافة فاريانت جديد -->
                    <button type="button" id="addVariantButton" class="btn btn-primary mb-4"><i
                            class="fa fa-plus"></i><span> اضافة تنوع للمنتج</span>
                    </button>
                </div>

            </div>
            <!-- /.card-body -->
            <div class="card-footer">
                <button type="submit" class="btn btn-info float-right">حفظ البيانات</button>
            </div>
            <!-- /.card-footer -->
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        // معاينة الصور قبل رفعها
        function previewImages(event) {
            var previewContainer = document.getElementById('imagePreviewContainer');
            previewContainer.innerHTML = ''; // مسح المعاينات السابقة

            if (event.target.files) {
                [...event.target.files].forEach(file => {
                    var reader = new FileReader();
                    reader.onload = function (e) {
                        var imgWrapper = document.createElement('div');
                        imgWrapper.className = 'm-2';

                        var img = document.createElement('img');
                        img.src = e.target.result;
                        img.className = 'img-thumbnail';
                        img.style = 'height: 60px; width: 60px; object-fit: cover;';

                        imgWrapper.appendChild(img);
                        previewContainer.appendChild(imgWrapper);
                    }
                    reader.readAsDataURL(file);
                });
            }
        }

        // إضافة الحدث عند تغيير الصور
        document.getElementById('inputImages').addEventListener('change', previewImages);

        $(document).ready(function () {
            var discountTypeSelector = $('#inputDiscountType');

            // اظهار أو إخفاء حقول الخصم بناءً على القيمة المحددة
            function toggleDiscountFields() {
                var selectedType = discountTypeSelector.val();
                if (selectedType === "") {
                    $('.discount-fields').hide(); // إخفاء الحقول
                    $('#inputDiscount').val(null); // تعيين قيمة الخصم إلى null
                    $('#inputStartDate').val(null); // تعيين تاريخ البدء إلى null
                    $('#inputEndDate').val(null); // تعيين تاريخ الانتهاء إلى null
                } else {
                    $('.discount-fields').show(); // إظهار الحقول عند اختيار نوع الخصم
                }
            }

            // استدعاء الوظيفة عند تغيير نوع الخصم
            discountTypeSelector.change(function () {
                toggleDiscountFields();
            });

            // تشغيل الوظيفة عند تحميل الصفحة لتعيين الحالة الأولية
            toggleDiscountFields();

            // تقديم النموذج عبر AJAX
            $('#product-form').submit(function (event) {
                event.preventDefault(); // منع إرسال النموذج بالطريقة التقليدية

                var formData = new FormData(this);

                // تحقق من القيم وإضافة قيم افتراضية إذا كانت الحقول مخفية
                if (discountTypeSelector.val() === "") {
                    formData.delete('discount'); // حذف قيمة الخصم
                    formData.delete('start_date'); // حذف تاريخ البدء
                    formData.delete('end_date'); // حذف تاريخ الانتهاء
                }

                $.ajax({
                    url: "{{ route('admin.products.store') }}",
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function (response) {
                        toastr.success(response.success);
                        resetForm();
                    },
                    error: function (xhr) {
                        if (xhr.status === 422) {
                            // إظهار أول خطأ فقط
                            var errors = xhr.responseJSON.errors;
                            var firstError = Object.values(errors)[0][0]; // أول خطأ
                            toastr.error(firstError);

                            // إظهار أول 3 أخطاء
                            // var errors = xhr.responseJSON.errors;
                            // var errorMessages = Object.values(errors).flat(); // تجميع جميع رسائل الأخطاء
                            //
                            // // عرض أول 3 أخطاء باستخدام توستر
                            // errorMessages.slice(0, 3).forEach(function(error) {
                            //     toastr.error(error);
                            // });

                        } else {
                            toastr.error('حدث خطأ غير متوقع.');
                        }
                    }

                });
            });

            function resetForm() {
                $('#product-form')[0].reset();
                $('.select2').val(null).trigger('change');
                $('#imagePreviewContainer').empty();
                $('#variantContainer').empty();

                $('html, body').animate({
                    scrollTop: $("#product-form").offset().top
                }, 500);
            }
        });

        // كود الفاريانت
        $(document).ready(function () {
            const maxOptions = {{$options->count()}};

            // عند الضغط على زر إضافة فاريانت جديد
            $('#addVariantButton').on('click', function () {
                let variantIndex = $('#variantContainer .variant-group').length;
                // جلب قيم سعر القطاعي والجملة من حقول المنتج
                const productPrice = $('#inputPrice').val() || '';
                const productGoomlaPrice = $('#inputGoomlaPrice').val() || '';

                const variantHtml = `
            <div class="variant-group" data-index="${variantIndex}">
                <span class="delete-variant text-danger">
                    <i class="fa fa-trash"></i>
                </span>

                <div class="option-group">
                    <select name="variants[${variantIndex}][options][0]" class="form-control option-select select2">
                        <option value="" selected disabled>اختر الاوبشن</option>
                        @foreach($options as $option)
                <option value="{{$option->id}}">{{$option->getTranslation('name', 'ar')}}</option>
                        @endforeach
                </select>

                <select name="variants[${variantIndex}][values][0]" class="form-control value-select select2">
                        <option value="">اختر القيمة</option>
                    </select>
                </div>

                <button type="button" class="btn btn-secondary add-option mt-3">
                    <i class="fa fa-plus"></i> إضافة اوبشن
                </button>

                <div class="form-group row mt-3">
                    <label  class="col-sm-2 form-label-sm">الكمية </label>
                    <div class="col-sm-10">
                        <input type="number" name="variants[${variantIndex}][quantity]" placeholder="ادخل الكمية " class="form-control" >
                         @error('variants[${variantIndex}][quantity]')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
            <div class="form-group row mt-3">
                <label  class="col-sm-2 form-label-sm">سعر القطاعي </label>
                <div class="col-sm-10">
                    <input type="number" name="variants[${variantIndex}][price]" placeholder=" سعر المنتج " class="form-control"  value="${productPrice}" >
                         @error('variants[${variantIndex}][price]')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
            <div class="form-group row mt-3">
                <label  class="col-sm-2 form-label-sm control-label">سعر الجملة </label>
                <div class="col-sm-10">
                    <input type="number" name="variants[${variantIndex}][goomla_price]" placeholder=" سعر الجملة " class="form-control" value="${productGoomlaPrice}" >
                         @error('variants[${variantIndex}][goomla_price]')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
            <div class="form-group row mt-3">
                <label  class="col-sm-2 form-label-sm"> الصور </label>
                <div class="col-sm-10">
                    <input type="file" name="variants[${variantIndex}][images][]" multiple class="form-control variant-images" >
                         @error('variants[${variantIndex}][images]')<div class="invalid-feedback">{{ $message }}</div>@enderror
                <div class="image-preview-container d-flex flex-wrap mt-2 gap-2"></div>
           </div>
       </div>

   </div>
`;

                $('#variantContainer').append(variantHtml);
            });

            // عند الضغط على أيقونة حذف الفاريانت
            $('#variantContainer').on('click', '.delete-variant', function () {
                $(this).closest('.variant-group').remove();
                updateVariantIndexes();
            });

            // إعادة ترتيب الفهارس بعد الحذف
            function updateVariantIndexes() {
                $('#variantContainer .variant-group').each(function (index) {
                    $(this).attr('data-index', index);
                    $(this).find('select, input, button').each(function () {
                        const nameAttr = $(this).attr('name');
                        if (nameAttr) {
                            const updatedName = nameAttr.replace(/variants\[\d+\]/, `variants[${index}]`);
                            $(this).attr('name', updatedName);
                        }
                    });
                });
            }

            // عند تغيير الخيار في القائمة المنسدلة
            $('#variantContainer').on('change', '.option-select', function () {
                const valueSelect = $(this).closest('.option-group').find('.value-select');
                const selectedOption = $(this).val();

                valueSelect.empty().append('<option value="" selected disabled>اختر القيمة</option>');

                $.ajax({
                    url: "{{route('admin.products.getOptionValues')}}",
                    method: 'GET',
                    data: {
                        option_id: selectedOption,
                    },
                    success: function (response) {
                        $(response).each(function (key, optionValue) {
                            valueSelect.append(`<option value="${optionValue.id}">${optionValue.value}</option>`);
                        });
                    },
                    error: function (xhr) {
                        console.log(xhr);
                    }
                });
            });

            // إضافة خيار آخر للفاريانت
            $('#variantContainer').on('click', '.add-option', function () {
                const variantGroup = $(this).closest('.variant-group');
                const currentIndex = variantGroup.data('index');
                const optionIndex = variantGroup.find('.option-group').length;

                if (optionIndex < maxOptions) {
                    // جمع الأوبشنات المختارة حالياً في نفس الفاريانت
                    const selectedOptions = variantGroup.find('.option-select').map(function () {
                        return $(this).val();
                    }).get();

                    // إنشاء خيارات الأوبشنات مع استبعاد المختار منها
                    let optionsHtml = '<option value="" selected disabled>اختر الاوبشن</option>';
                    @foreach($options as $option)
                    if (!selectedOptions.includes("{{$option->id}}")) {
                        optionsHtml += `<option value="{{$option->id}}">{{$option->getTranslation('name', 'ar') }}</option>`;
                    }
                    @endforeach

                    const newOptionHtml = `
            <div class="option-group">
                <select name="variants[${currentIndex}][options][${optionIndex}]" class="form-control option-select select2">
                    ${optionsHtml}
                </select>

                <select name="variants[${currentIndex}][values][${optionIndex}]" class="form-control value-select select2">
                    <option value="" selected disabled>اختر القيمة</option>
                </select>

                <span class="text-danger remove-option">
                    <i class="fa fa-trash"></i>
                </span>
            </div>
        `;

                    // إضافة الأوبشن الجديد فقط إذا كان هناك خيارات متاحة
                    if (selectedOptions.length < maxOptions) {
                        $(this).before(newOptionHtml);

                        // تهيئة السيلكت2 للسيلكت الجديد
                        // variantGroup.find('.option-select').last().select2();
                        // variantGroup.find('.value-select').last().select2();
                    }
                }

                // تعطيل زر إضافة أوبشن جديد إذا تم اختيار كل الأوبشنات
                if (variantGroup.find('.option-group').length >= maxOptions) {
                    $(this).prop('disabled', true);
                }
            });
// تحديث الأوبشنات المتاحة عند حذف أوبشن
            $('#variantContainer').on('click', '.remove-option', function () {
                const variantGroup = $(this).closest('.variant-group');
                const removedOption = $(this).closest('.option-group').find('.option-select').val();
                $(this).closest('.option-group').remove();

                // إعادة تمكين زر الإضافة
                variantGroup.find('.add-option').prop('disabled', false);

                // تحديث الأوبشنات المتاحة في باقي السيلكت
                const remainingSelects = variantGroup.find('.option-select');
                const selectedOptions = remainingSelects.map(function () {
                    return $(this).val();
                }).get();

                // إضافة الأوبشن المحذوف لباقي السيلكت
                if (removedOption) {
                    @foreach($options as $option)
                    if ("{{$option->id}}" === removedOption) {
                        remainingSelects.each(function () {
                            if (!$(this).find(`option[value="${removedOption}"]`).length) {
                                $(this).append(`<option value="{{$option->id}}">{{$option->getTranslation('name', 'ar')}}</option>`);
                            }
                        });
                    }
                    @endforeach
                }
            });
            // إضافة معالج حدث تغيير الصور
            $('#variantContainer').on('change', '.variant-images', function (e) {
                const previewContainer = $(this).siblings('.image-preview-container');
                previewContainer.empty(); // مسح البريفيو السابق

                const files = e.target.files;

                for (let i = 0; i < files.length; i++) {
                    const file = files[i];
                    if (file && file.type.startsWith('image/')) {
                        const reader = new FileReader();

                        reader.onload = function (e) {
                            const preview = $(`
                        <div class="position-relative">
                            <img src="${e.target.result}"
                                 style="width: 50px; height: 50px; object-fit: cover;"
                                 class="border rounded">
                        </div>
                    `);

                            previewContainer.append(preview);
                        };

                        reader.readAsDataURL(file);
                    }
                }
            });

        });

    </script>
@endpush
@push('styles')
    <style>
        /* تنسيق صندوق الفاريانت */
        .variant-group {
            border: 3px solid #ddd;
            padding: 22px 15px 27px 15px;
            margin-bottom: 20px;
            position: relative;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            background-color: #eeeeee40;
        }

        /* أيقونة حذف الفاريانت */
        .delete-variant {
            position: absolute;
            bottom: 0;
            right: 50%;
            cursor: pointer;
            z-index: 10;
            font-size: 14px;
        }

        /* تنسيق مجموعة الأوبشن */
        .option-group {
            border: 1px solid #e3e6f0;
            padding: 10px 10px 10px 20px;
            border-radius: 6px;
            margin-top: 10px;
            background-color: #fdfdfd;
            display: flex;
            align-items: center;
            gap: 10px; /* مسافة بين العناصر */
            position: relative;
        }

        .option-group select {
            font-size: 16px;
            font-weight: bold;
            padding-left: 0;
            padding-right: 5px;
        }

        .option-group select option {
            font-size: 14px;
            font-weight: bold;
        }

        /* اوبشن سلكت الكي */
        .option-select, .value-select {
            flex: 1; /* يجعلهم بنفس الحجم */
            margin-bottom: 0; /* إزالة المسافة السفلية */
        }

        /* أيقونة حذف الأوبشن */
        .remove-option {
            cursor: pointer;
            position: absolute;
            left: 4px;
            top: 50%;
            transform: translateY(-50%);
            color: #dc3545;
            font-size: 13px;
            z-index: 999;
        }

        .remove-option:hover {
            color: #a71d2a;
        }

        /* زر إضافة أوبشن */
        .btn.add-option {
            margin-top: 10px;
            background-color: #007bff;
            color: #fff;
            padding: 8px 12px;
            border-radius: 4px;
            font-size: 0.9em;
        }

        .btn.add-option:hover {
            background-color: #0056b3;
        }

        /* زر إضافة فاريانت */
        #addVariantButton {
            display: block;
            margin: 20px auto;
        }

        #addVariantButton i {
            padding-left: 8px;
        }

        /* تنسيق زر إضافة أوبشن مع الأيقونة */
        .btn.add-option {
            background-color: #007bff;
            color: #fff;
            padding: 8px 12px;
            border-radius: 4px;
            font-size: 0.9em;
            display: flex;
            align-items: center;
            gap: 8px; /* مسافة بين الأيقونة والنص */
        }

        .btn.add-option i {
            font-size: 1.1em;
        }

        .btn.add-option:hover {
            background-color: #0056b3;
        }

        /* تنسيق الصور المصغرة */
        .image-preview-container {
            display: flex;
            gap: 8px;
            margin-top: 10px;
            flex-wrap: wrap;
        }

        .preview-image {
            width: 45px;
            height: 45px;
            object-fit: cover;
            border: 2px solid #ddd;
            border-radius: 6px;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
        }

        .product-image-preview {
            margin-bottom: 5px;
        }

        .product-images-div {
            margin-bottom: 2px;
        }

        .form-label-sm {
            font-size: 15px !important;
        }


    </style>
@endpush

