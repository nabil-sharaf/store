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
                    <label for="inputPrice" class="col-sm-2 control-label">السعر</label>
                    <div class="col-sm-10">
                        <input type="number" step="0.01" class="form-control @error('price') is-invalid @enderror"
                               id="inputPrice" placeholder="أدخل سعر المنتج" name='price'
                               value="{{ old('price', $product->price) }}">
                        @error('price')
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
                <div class="form-group row mt-4">
                    <label for="inputCategories" class="col-sm-2 control-label">الفئات</label>
                    <div class="col-sm-10">
                        <select multiple class="form-control @error('categories') is-invalid @enderror"
                                id="inputCategories" name="categories[]">
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
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
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
            // مسح المعاينات السابقة
            previewContainer.html('');

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
                var url = removeImageUrl.replace(':id', imageId);
                console.log('Sending request to:', url); // لتسجيل الرابط في وحدة التحكم

                $.ajax({
                    url: url,
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    success: function(data) {
                        console.log('Response data:', data); // لتسجيل بيانات الاستجابة
                        if (data.success) {
                            $(button).parent().remove();
                        } else {
                            alert('حدث خطأ أثناء حذف الصورة: ' + data.message);
                        }
                    },
                    error: function(xhr) {
                        console.error('Error:', xhr);
                        alert('حدث خطأ أثناء حذف الصورة: ' + xhr.statusText);
                    }
                });
            }
        }


    </script>
@endpush
