@extends('admin.layouts.app')
@section('page-title')
    تعديل المنتج
@endsection
@section('content')
    @if ($errors->any())
        <div class="alert alert-danger">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif
    <div class="card card-info">
        <div class="card-header">
            <h3 class="card-title float-left">تعديل المنتج</h3>
        </div>
        <!-- /.card-header -->
        <!-- form start -->
        <form class="form-horizontal" method="POST" id="product-edit-form" data-product-id="{{$product->id}}"
              enctype="multipart/form-data" dir="rtl"
{{--              action="{{route('admin.products.update', $product->id)}}"--}}
        >
            @csrf
{{--            @method('PUT')--}}
            <div class="card-body">
                <div class="form-group row">
                    <label for="inputName" class="col-sm-2 control-label">اسم المنتج</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="inputName"
                               placeholder="أدخل اسم المنتج" name='name' value="{{ old('name', $product->name) }}">
                        @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="form-group row mt-4">
                    <label for="inputDescription" class="col-sm-2 control-label">الوصف</label>
                    <div class="col-sm-10">
                        <textarea class="form-control @error('description') is-invalid @enderror" id="inputDescription"
                                  rows="3" placeholder="أدخل وصف المنتج"
                                  name='description'>{{ old('description', $product->description) }}</textarea>
                        @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="form-group row mt-4">
                    <label for="inputInfo" class="col-sm-2 control-label">معلومات المنتج</label>
                    <div class="col-sm-10">
                        <textarea class="form-control @error('info') is-invalid @enderror" id="inputInfo"
                                  rows="3" placeholder="أدخل وصف المنتج"
                                  name='info'>{{ old('info', $product->info) }}</textarea>
                        @error('info')
                        <div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="form-group row mt-4">
                    <label for="inputPrice" class="col-sm-2 control-label">السعر</label>
                    <div class="col-sm-10">
                        <input type="number" step="0.01" class="form-control @error('price') is-invalid @enderror"
                               id="inputPrice" placeholder="أدخل سعر المنتج" name='price'
                               value="{{ old('price', $product->price) }}">
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
                               value="{{ old('price', $product->goomla_price) }}">
                        @error('goomla_price')
                        <div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="form-group row mt-4">
                    <label for="inputQuantity" class="col-sm-2 control-label">الكمية</label>
                    <div class="col-sm-10">
                        <input type="number" class="form-control @error('quantity') is-invalid @enderror"
                               id="inputQuantity" placeholder="أدخل كمية المنتج" name='quantity'
                               value="{{ old('quantity', $product->quantity) }}" required min="1">
                        @error('quantity')
                        <div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <!-- إضافة حقل اختيار الفئات -->
                <div class="form-group row mt-4">
                    <label for="inputCategories" class="col-sm-2 control-label">الفئات</label>
                    <div class="col-sm-10">
                        <select multiple class="form-control select2  @error('categories') is-invalid @enderror"
                                id="inputCategories" name="categories[]" style="width: 100%">
                            @foreach($categories as $category)
                                <option
                                    value="{{ $category->id }}" {{ (in_array($category->id, old('categories', $product->categories->pluck('id')->toArray()))) ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('categories')
                        <div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                {{--  البريفيكس --}}
                <div class="form-group row mt-4">
                    <label for="prefix" class="col-sm-2 control-label"> البريفكس </label>
                    <div class="col-sm-10">
                        <select class="form-control select2  @error('prefix_id') is-invalid @enderror" id="prefix"
                                name="prefix_id" style="width: 100%">
                            @foreach($prefixes as $prefix)
                                <option
                                    value="{{ $prefix->id }}" {{ old('prefix_id',$prefix->id )== $product->prefix?->id ? 'selected' : '' }}>
                                    {{ $prefix->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('prefix_id')
                        <div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                <!-- إضافة الحقول الجديدة للخصم وتواريخه -->
                <div class="form-group row mt-4">
                    <label for="inputDiscountType" class="col-sm-2 control-label">نوع الخصم</label>
                    <div class="col-sm-10">
                        <select class="select2 form-control @error('discount_type') is-invalid @enderror"
                                id="inputDiscountType" name="discount_type">
                            <option
                                value="" {{ old('discount_type', $product->discount?->discount_type) == '' ? 'selected' : '' }}>
                                بدون خصم
                            </option>
                            <option
                                value="fixed" {{ old('discount_type', $product->discount?->discount_type) == 'fixed' ? 'selected' : '' }}>
                                ثابت
                            </option>
                            <option
                                value="percentage" {{ old('discount_type', $product->discount?->discount_type) == 'percentage' ? 'selected' : '' }}>
                                نسبة مئوية
                            </option>
                        </select>
                        @error('discount_type')
                        <div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="form-group row mt-4 discount-fields" style="display: none;">
                    <label for="inputDiscount" class="col-sm-2 control-label">قيمة الخصم</label>
                    <div class="col-sm-10">
                        <input type="number" step="0.01" class="form-control @error('discount') is-invalid @enderror"
                               id="inputDiscount" placeholder="أدخل قيمة الخصم" name='discount'
                               value="{{ old('discount', $product?->discount?->discount) }}" min="0">
                        @error('discount')
                        <div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="form-group row mt-4 discount-fields" style="display: none;">
                    <label for="inputStartDate" class="col-sm-2 control-label">تاريخ البدء</label>
                    <div class="col-sm-10">
                        <input type="date" class="form-control @error('start_date') is-invalid @enderror"
                               id="inputStartDate" name='start_date'
                               value="{{ old('start_date', $product?->discount?->start_date?->format('Y-m-d')) }}">
                        @error('start_date')
                        <div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="form-group row mt-4 discount-fields" style="display: none;">
                    <label for="inputEndDate" class="col-sm-2 control-label">تاريخ الانتهاء</label>
                    <div class="col-sm-10">
                        <input type="date" class="form-control @error('end_date') is-invalid @enderror"
                               id="inputEndDate" name='end_date'
                               value="{{ old('end_date', $product?->discount?->end_date?->format('Y-m-d')) }}">
                        @error('end_date')
                        <div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <!-- الحقول السابقة للصور وغيرها -->
                <div class="form-group row mt-4">
                    <label for="inputImages" class="col-sm-2 control-label">صور المنتج</label>
                    <div class="col-sm-10">
                        <input type="file" class="form-control-file @error('images') is-invalid @enderror"
                               id="inputImages" name="images[]" multiple accept="image/*"
                               onchange="previewImages(event)">
                        @error('images')
                        <div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="form-group row mt-4">
                    <div class="col-sm-2"></div>
                    <div class="col-sm-10">
                        <div id="imagePreviewContainer" class="d-flex flex-wrap gap-3">
                            @foreach($product->images as $image)
                                <div class="image-card p-2 border rounded m-2">
                                    <div class="d-flex flex-column align-items-center">
                                        <img src="{{ asset('storage/' . $image->path) }}"
                                             alt="Image Preview"
                                             class="rounded mb-2"
                                             style="max-width: 80px; height: 60px; object-fit: cover; margin-bottom:0 !important;">
                                        <button type="button"
                                                class="btn btn-danger btn-sm delete-btn"
                                                onclick="removeImage(this, {{ $image->id }})">
                                            <i class="fas fa-trash-alt me-1"></i> حذف
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                            <div id="newImagePreviewContainer" class="d-flex flex-wrap gap-3">
                                <!-- معاينات الصور الجديدة -->
                            </div>
                        </div>
                    </div>
                </div>

                <!-- الفاريانتات -->
                <div class="container mt-4">
                    <div id="variantContainer">
                        @foreach($product->variants as $index => $variant)
                            <div class="variant-group border p-3 mb-3 position-relative" data-index="{{ $index }}"
                                 data-variant-id="{{ $variant->id }}">
                                <span class="delete-variant text-danger position-absolute top-0 end-0 m-2"
                                      style="cursor: pointer;">
                                    <i class="fa fa-trash"></i>
                                </span>

                                @foreach($variant->optionValues as $optionIndex => $optionValue)
                                    <div class="option-group">
                                        <select name="variants[{{ $index }}][options][{{ $optionIndex }}]"
                                                class="form-control option-select select2">
                                            <option value="">اختر الاوبشن</option>
                                            @foreach($options as $option)
                                                <option value="{{ $option->id }}"
                                                    {{ $optionValue->option_id == $option->id ? 'selected' : '' }}>
                                                    {{ $option->getTranslation('name', 'ar') }}
                                                </option>
                                            @endforeach
                                        </select>

                                        <select name="variants[{{ $index }}][values][{{ $optionIndex }}]"
                                                class="form-control value-select select2">
                                            <option value="">اختر القيمة</option>
                                            @foreach($optionValue->option->optionValues as $value)
                                                <option value="{{ $value->id }}"
                                                    {{ $optionValue->id == $value->id ? 'selected' : '' }}>
                                                    {{ $value->value }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @if($optionIndex > 0)
                                            <span class="text-danger remove-option" style="cursor: pointer;">
                                <i class="fa fa-trash"></i>
                            </span>
                                        @endif
                                    </div>
                                @endforeach

                                <button type="button" class="btn btn-secondary add-option mt-3">
                                    <i class="fa fa-plus"></i> إضافة اوبشن
                                </button>

                                <div class="form-group row mt-3">
                                    <label class="col-sm-2 form-label-sm">الكمية</label>
                                    <div class="col-sm-10">
                                        <input type="number" name="variants[{{ $index }}][quantity]"
                                               value="{{ $variant->quantity }}" class="form-control">
                                    </div>
                                </div>

                                <div class="form-group row mt-3">
                                    <label class="col-sm-2 form-label-sm">سعر القطاعي</label>
                                    <div class="col-sm-10">
                                        <input type="number" name="variants[{{ $index }}][price]"
                                               value="{{ $variant->price }}" class="form-control">
                                    </div>
                                </div>

                                <div class="form-group row mt-3">
                                    <label class="col-sm-2 form-label-sm">سعر الجملة</label>
                                    <div class="col-sm-10">
                                        <input type="number" name="variants[{{ $index }}][goomla_price]"
                                               placeholder="سعر الجملة" class="form-control"
                                               value="{{ $variant->goomla_price }}">
                                        @error("variants[{{$index}}][goomla_price]")
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row mt-3">
                                    <label class="col-sm-2 form-label-sm">الصور</label>
                                    <div class="col-sm-10">
                                        <input type="file" name="variants[{{ $index }}][images][]" multiple
                                               class="form-control variant-images">
                                        @error("variants[{{$index}}][images]")
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <div class="image-preview-container">
                                            <div class="image-preview">
                                                <div class="d-flex flex-wrap gap-3"> <!-- زيادة الفجوة بين العناصر -->
                                                    @foreach($variant->images as $image)
                                                        <div class="image-card p-2 border rounded m-2">
                                                            <!-- إضافة كارد للصورة -->
                                                            <div class="d-flex flex-column align-items-center">
                                                                <img src="{{asset('storage/'.$image->path)}}"
                                                                     alt="Image Preview"
                                                                     class="rounded mb-2"
                                                                     style="margin-bottom:0!important; max-height: 60px; max-width: 80px; object-fit: cover;"/>
                                                                <button type="button"
                                                                        class="btn btn-danger btn-sm delete-btn"
                                                                        onclick="removeImage(this, {{ $image->id }})">
                                                                    <i class="fas fa-trash-alt me-1"></i> حذف
                                                                </button>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                    <div class="newImagePreviewContainer d-flex flex-wrap gap-3">
                                                        <!-- سيتم إضافة الصور الجديدة هنا -->
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <input type="hidden" name="variants[{{$index}}][id]" value="{{ $variant->id ?? '' }}">
                        @endforeach
                    </div>
                </div>
                <!-- زر إضافة فاريانت جديد -->
                <button type="button" id="addVariantButton" class="btn btn-primary mb-4">
                    <i class="fa fa-plus"></i>
                    <span>اضافة تنوع للمنتج</span>
                </button>
            </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-info float-right">حفظ التعديلات</button>
                </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        function previewImages(event) {
            var previewContainer = $('#newImagePreviewContainer');
            previewContainer.html(''); // مسح المعاينات السابقة

            if (event.target.files) {
                $.each(event.target.files, function (_, file) {
                    var reader = new FileReader();
                    reader.onload = function (e) {
                        var imgWrapper = $('<div>').addClass('m-2');
                        var img = $('<img>').attr('src', e.target.result)
                            .addClass('img-thumbnail')
                            .css({height: '100px', width: '100px', objectFit: 'cover'});
                        imgWrapper.append(img);
                        previewContainer.append(imgWrapper);
                    }
                    reader.readAsDataURL(file);
                });
            }
        }

        // دالة جديدة لحذف الصور الجديدة (قبل الحفظ)


        var removeImageUrl = "{{ route('admin.products.remove-image', ':id') }}";

        function removeImage(button, imageId) {
            let csrfToken = $('input[name="_token"]').val();
            if (confirm('هل أنت متأكد من حذف هذه الصورة؟')) {
                $.ajax({
                    url: removeImageUrl.replace(':id', imageId),
                    type: 'DELETE',
                    data: {_token: csrfToken},
                    success: function (response) {
                        if (response.success) {
                            $(button).closest('.m-2').remove();
                        } else {
                            alert('حدث خطأ أثناء حذف الصورة.');
                        }
                    },
                    error: function () {
                        alert('حدث خطأ أثناء حذف الصورة.');
                    }
                });
            }
        }

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

        });

        // كود الفاريانت
        $(document).ready(function () {
            const maxOptions = {{$options->count()}};
            // إضافة فاريانت جديد
            $(document).on('click', '#addVariantButton', function (e) {
                e.preventDefault(); // Add this to prevent any default behavior

                const variantIndex = $('#variantContainer .variant-group').length;
                const productPrice = $('#inputPrice').val() || '';
                const productGoomlaPrice = $('#inputGoomlaPrice').val() || '';

                const variantHtml = `
            <div class="variant-group border p-3 mb-3 position-relative" data-index="${variantIndex}">
                <span class="delete-variant text-danger position-absolute top-0 end-0 m-2" style="cursor: pointer;">
                    <i class="fa fa-trash"></i>
                </span>

                <div class="option-group">
                    <select name="variants[${variantIndex}][options][0]" class="form-control option-select">
                        <option value="" selected disabled>اختر الاوبشن</option>
                        @foreach($options as $option)
                <option value="{{$option->id}}">{{$option->getTranslation('name', 'ar')}}</option>
                        @endforeach
                </select>

                <select name="variants[${variantIndex}][values][0]" class="form-control value-select">
                        <option value="">اختر القيمة</option>
                    </select>
                </div>

                <button type="button" class="btn btn-secondary add-option mt-3">
                    <i class="fa fa-plus"></i> إضافة اوبشن
                </button>

                <div class="form-group row mt-3">
                    <label class="col-sm-2 form-label-sm">الكمية</label>
                    <div class="col-sm-10">
                        <input type="number" name="variants[${variantIndex}][quantity]" class="form-control">
                    </div>
                </div>

                <div class="form-group row mt-3">
                    <label class="col-sm-2 form-label-sm">سعر القطاعي</label>
                    <div class="col-sm-10">
                        <input type="number" name="variants[${variantIndex}][price]" class="form-control" value="${productPrice}">
                    </div>
                </div>

                <div class="form-group row mt-3">
                    <label class="col-sm-2 form-label-sm">سعر الجملة</label>
                    <div class="col-sm-10">
                        <input type="number" name="variants[${variantIndex}][goomla_price]" class="form-control" value="${productGoomlaPrice}">
                    </div>
                </div>

                <div class="form-group row mt-3">
                    <label class="col-sm-2 form-label-sm">الصور</label>
                    <div class="col-sm-10">
                        <input type="file" name="variants[${variantIndex}][images][]" multiple class="form-control variant-images">
                        <div class="image-preview-container d-flex flex-wrap mt-2 gap-2"></div>
                    </div>
                </div>
            </div>
        `;

                $('#variantContainer').append(variantHtml);

                // Initialize select2 for new selects if you're using select2
                const newVariant = $(`#variantContainer .variant-group[data-index="${variantIndex}"]`);
                if ($.fn.select2) {
                    newVariant.find('select').select2();
                }

                // Update indexes
                updateVariantIndexes();
            });

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


            // حذف الفاريانت - تم تعديل السيليكتور ليعمل على مستوى الدوكيومنت
            $(document).on('click', '.delete-variant', function () {
                const variantGroup = $(this).closest('.variant-group');
                const variantId = variantGroup.data('variant-id');

                if (variantId) {
                    if (confirm('هل أنت متأكد من حذف هذا الفاريانت؟')) {
                        variantGroup.remove();
                        updateVariantIndexes();
                    }
                } else {
                    variantGroup.remove();
                    updateVariantIndexes();
                }
            });

            // إضافة اوبشن جديد مع الحفاظ على التنسيقات الأصلية
            $(document).on('click', '.add-option', function () {
                const variantGroup = $(this).closest('.variant-group');
                const currentIndex = variantGroup.data('index');
                const optionGroups = variantGroup.find('.option-group');
                const optionIndex = optionGroups.length;

                if (optionIndex < maxOptions) {
                    const selectedOptions = optionGroups.find('.option-select').map(function () {
                        return $(this).val();
                    }).get();

                    let optionsHtml = '<option value="" selected disabled>اختر الاوبشن</option>';
                    @foreach($options as $option)
                    if (!selectedOptions.includes("{{$option->id}}")) {
                        optionsHtml += `<option value="{{$option->id}}">{{$option->getTranslation('name', 'ar')}}</option>`;
                    }
                    @endforeach

                    const newOptionHtml = `
                <div class="option-group mb-2">
                            <select name="variants[${currentIndex}][options][${optionIndex}]"
                                   class="form-control option-select select2">
                                ${optionsHtml}
                            </select>

                            <select name="variants[${currentIndex}][values][${optionIndex}]"
                                    class="form-control value-select select2">
                                <option value="">اختر القيمة</option>
                            </select>

                            <span class="text-danger remove-option" style="cursor: pointer;">
                                <i class="fa fa-trash"></i>
                            </span>

                </div>
            `;

                    // إضافة الاوبشن الجديد قبل زر الإضافة
                    $(this).before(newOptionHtml);

                    // تفعيل Select2 على السيلكت بوكس الجديد إذا كان مستخدماً
                    if ($.fn.select2) {
                        variantGroup.find('.option-select:not(.select2-hidden-accessible), .value-select:not(.select2-hidden-accessible)').select2();
                    }

                    // تعطيل زر الإضافة إذا وصلنا للحد الأقصى
                    if (variantGroup.find('.option-group').length >= maxOptions) {
                        $(this).prop('disabled', true);
                    }
                }
            });

            // تحديث حدث حذف الاوبشن
            $(document).on('click', '.remove-option', function () {
                const variantGroup = $(this).closest('.variant-group');
                $(this).closest('.option-group').remove();

                // إعادة تفعيل زر إضافة الاوبشن
                const addOptionButton = variantGroup.find('.add-option');
                addOptionButton.prop('disabled', false);

                // إعادة ترتيب الفهارس
                updateOptionIndexes(variantGroup);
            });

            // دالة تحديث فهارس الاوبشنز
            function updateOptionIndexes(variantGroup) {
                const currentIndex = variantGroup.data('index');
                variantGroup.find('.option-group').each(function (optionIndex) {
                    $(this).find('select').each(function () {
                        const nameAttr = $(this).attr('name');
                        if (nameAttr) {
                            const updatedName = nameAttr.replace(
                                /variants\[\d+\]\[(options|values)\]\[\d+\]/,
                                `variants[${currentIndex}][$1][${optionIndex}]`
                            );
                            $(this).attr('name', updatedName);
                        }
                    });
                });
            }

            // تحديث فهارس الفاريانت
            function updateVariantIndexes() {
                $('#variantContainer .variant-group').each(function (index) {
                    $(this).attr('data-index', index);
                    $(this).find('select, input').each(function () {
                        const nameAttr = $(this).attr('name');
                        if (nameAttr) {
                            const updatedName = nameAttr.replace(
                                /variants\[\d+\]/,
                                `variants[${index}]`
                            );
                            $(this).attr('name', updatedName);
                        }
                    });
                });
            }

            // معالجة حدث تغيير الصور
            $('#variantContainer').on('change', '.variant-images', function (e) {
                // تحديد حاوية البريفيو
                const previewContainer = $(this)
                    .closest('.form-group')
                    .find('.image-preview-container')
                    .find('.newImagePreviewContainer');

                const files = e.target.files;

                // مسح الصور السابقة في حاوية البريفيو الجديدة
                previewContainer.empty();

                // التأكد من وجود الحاوية
                if (previewContainer.length === 0) {
                    console.error('Preview container not found');
                    return;
                }

                // إنشاء صف جديد للصور
                const imageRow = $('<div class="d-flex flex-wrap gap-2"></div>');
                previewContainer.append(imageRow);

                for (let i = 0; i < files.length; i++) {
                    const file = files[i];
                    if (file && file.type.startsWith('image/')) {
                        const reader = new FileReader();

                        reader.onload = function (e) {
                            const preview = $(`
                    <div class=" var-rev-image d-flex flex-column align-items-center">
                        <img src="${e.target.result}"
                             alt="Image Preview"
                             style="max-width: 80px; max-height: 60px; margin-bottom: 5px;" />
                    </div>
                `);
                            imageRow.append(preview);
                        };

                        reader.readAsDataURL(file);
                    }
                }
            });

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // تحديث الفورم
            $('#product-edit-form').submit(function (e) {
                e.preventDefault();
                const formData = new FormData(this);
                const productId = $(this).data('product-id');
                const submitButton = $(this).find('button[type="submit"]');

                // تعطيل زر الـ Submit
                submitButton.prop('disabled', true);

                $.ajax({
                    url: "{{route('admin.products.update.ajax',':id')}}".replace(':id', productId),
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (response) {
                        // إعادة التوجيه إلى صفحة الـ Index بعد نجاح التحديث
                        window.location.href = "{{ route('admin.products.index') }}";
                    },
                    error: function (xhr) {
                        if (xhr.status === 422) {
                            const errors = xhr.responseJSON.errors;
                            const firstError = Object.values(errors)[0][0];
                            toastr.error(firstError);
                        } else {
                            toastr.error('حدث خطأ غير متوقع');
                        }
                    },
                    complete: function () {
                        // إعادة تفعيل زر الـ Submit بعد انتهاء الطلب
                        submitButton.prop('disabled', false);
                    }
                });
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

        .image-card {
            background: #fff;
            transition: all 0.3s ease;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .image-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
        }

        .delete-btn {
            transition: all 0.2s ease;
            padding: 0.25rem 0.75rem;
            font-size: 0.875rem;
            border-radius: 4px;
            width: 100%;
        }

        .delete-btn:hover {
            background-color: #dc3545;
            transform: scale(1.05);
        }

        .delete-btn:active {
            transform: scale(0.95);
        }

        .var-rev-image{
            padding-top:10%;
        }
    </style>
@endpush
