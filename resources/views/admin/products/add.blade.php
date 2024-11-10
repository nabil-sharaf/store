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
                                    {{ $prefix->description }}
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
                <div class="form-group row mt-4">
                    <label for="inputImages" class="col-sm-2 control-label">صور المنتج</label>
                    <div class="col-sm-10">
                        <input type="file" class="form-control-file @error('images') is-invalid @enderror"
                               id="inputImages" name="images[]" multiple accept="image/*">
                        @error('images')
                        <div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <!-- قسم لمعاينة الصور -->
                <div class="form-group row mt-4">
                    <div class="col-sm-2"></div>
                    <div class="col-sm-10">
                        <div id="imagePreviewContainer" class="d-flex flex-wrap"></div>
                    </div>
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
                        img.style = 'height: 100px; width: 100px; object-fit: cover;';

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

                $('html, body').animate({
                    scrollTop: $("#product-form").offset().top
                }, 500);
            }
        });



    </script>
@endpush
