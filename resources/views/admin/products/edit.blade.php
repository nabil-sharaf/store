@extends('admin.layouts.app')
@section('page-title')
    تعديل المنتج
@endsection
@section('content')

    <div class="card card-info">
        <div class="card-header">
            <h3 class="card-title float-left">تعديل المنتج</h3>
        </div>
        <!-- /.card-header -->
        <!-- form start -->
        <form class="form-horizontal" action="{{ route('admin.products.update', $product->id) }}" method="POST"
              enctype="multipart/form-data" dir="rtl">
            @method('PUT')
            @csrf
            <div class="card-body">
                <!-- الحقول السابقة مثل اسم المنتج، الوصف، السعر، الكمية -->
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
                        <input type="number" step="0.01" class="form-control @error('goomla_price') is-invalid @enderror" id="inputGoomlaPrice" placeholder="أدخل سعر الجملة للمنتج" name='goomla_price' value="{{ old('price', $product->goomla_price) }}">
                        @error('goomla_price')<div class="invalid-feedback">{{ $message }}</div>@enderror
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
                        <select  multiple class="form-control select2  @error('categories') is-invalid @enderror" id="inputCategories" name="categories[]" style="width: 100%">
                            @foreach($categories as $category)
                                <option
                                    value="{{ $category->id }}" {{ (in_array($category->id, old('categories', $product->categories->pluck('id')->toArray()))) ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('categories')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                <!-- إضافة الحقول الجديدة للخصم وتواريخه -->
                <div class="form-group row mt-4">
                    <label for="inputDiscountType" class="col-sm-2 control-label">نوع الخصم</label>
                    <div class="col-sm-10">
                        <select class="select2 form-control @error('discount_type') is-invalid @enderror"
                                id="inputDiscountType" name="discount_type">
                            <option value="" {{ old('discount_type', $product->discount?->discount_type) == '' ? 'selected' : '' }}>بدون خصم</option>
                            <option value="fixed" {{ old('discount_type', $product->discount?->discount_type) == 'fixed' ? 'selected' : '' }}>ثابت</option>
                            <option value="percentage" {{ old('discount_type', $product->discount?->discount_type) == 'percentage' ? 'selected' : '' }}>نسبة مئوية</option>
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
                        <div id="imagePreviewContainer" class="d-flex flex-wrap">
                            @foreach($product->images as $image)
                                <div class="m-2">
                                    <img src="{{ asset('storage/' . $image->path) }}" class="img-thumbnail"
                                         style="height: 100px; width: 100px; object-fit: cover;">
                                    <button type="button" class="btn btn-sm btn-danger"
                                            onclick="removeImage(this, {{ $image->id }})">حذف
                                    </button>
                                </div>
                            @endforeach
                            <div id="newImagePreviewContainer" class="d-flex flex-wrap mt-2">
                                <!-- معاينات الصور الجديدة -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.card-body -->
            <div class="card-footer">
                <button type="submit" class="btn btn-info float-right">حفظ التعديلات</button>
            </div>
            <!-- /.card-footer -->
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        function previewImages(event) {
            var previewContainer = $('#newImagePreviewContainer');
            previewContainer.html(''); // مسح المعاينات السابقة

            if (event.target.files) {
                $.each(event.target.files, function(_, file) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        var imgWrapper = $('<div>').addClass('m-2');
                        var img = $('<img>').attr('src', e.target.result)
                            .addClass('img-thumbnail')
                            .css({ height: '100px', width: '100px', objectFit: 'cover' });
                        imgWrapper.append(img);
                        previewContainer.append(imgWrapper);
                    }
                    reader.readAsDataURL(file);
                });
            }
        }

        var removeImageUrl = "{{ route('admin.products.remove-image', ':id') }}";

        function removeImage(button, imageId) {
            let csrfToken = $('input[name="_token"]').val();
            if (confirm('هل أنت متأكد من حذف هذه الصورة؟')) {
                $.ajax({
                    url: removeImageUrl.replace(':id', imageId),
                    type: 'DELETE',
                    data: { _token: csrfToken },
                    success: function(response) {
                        if (response.success) {
                            $(button).closest('.m-2').remove();
                        } else {
                            alert('حدث خطأ أثناء حذف الصورة.');
                        }
                    },
                    error: function() {
                        alert('حدث خطأ أثناء حذف الصورة.');
                    }
                });
            }
        }

        $(document).ready(function(){


            // إخفاء أو إظهار الحقول بناءً على قيمة نوع الخصم عند تحميل الصفحة
            toggleDiscountFields();

            // تنفيذ الدالة عند تغيير نوع الخصم
            $('#inputDiscountType').change(function () {
                toggleDiscountFields();
            });

            // دالة لإخفاء أو إظهار الحقول
            function toggleDiscountFields() {
                var discountType = $('#inputDiscountType').val();

                if (discountType === '') {
                    // إخفاء الحقول وتفريغ القيم عند اختيار "بدون خصم"
                    $('.discount-fields').hide();
                    $('#inputDiscount').val(null);
                    $('#inputStartDate').val(null);
                    $('#inputEndDate').val(null);
                } else {
                    // إظهار الحقول عند اختيار "ثابت" أو "نسبة مئوية"
                    $('.discount-fields').show();
                }
            }


        });
    </script>
@endpush
