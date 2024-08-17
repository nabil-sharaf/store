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
    <form class="form-horizontal" action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" dir="rtl">
        @csrf
        <div class="card-body">
            <!-- الحقول السابقة -->
            <div class="form-group row">
                <label for="inputName" class="col-sm-2 control-label">اسم المنتج</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="inputName" placeholder="أدخل اسم المنتج" name='name' value="{{ old('name') }}">
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
            <div class="form-group row mt-4">
                <label for="inputDescription" class="col-sm-2 control-label">الوصف</label>
                <div class="col-sm-10">
                    <textarea class="form-control @error('description') is-invalid @enderror" id="inputDescription" rows="3" placeholder="أدخل وصف المنتج" name='description'>{{ old('description') }}</textarea>
                    @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
            <div class="form-group row mt-4">
                <label for="inputPrice" class="col-sm-2 control-label">السعر</label>
                <div class="col-sm-10">
                    <input type="number" step="0.01" class="form-control @error('price') is-invalid @enderror" id="inputPrice" placeholder="أدخل سعر المنتج" name='price' value="{{ old('price') }}">
                    @error('price')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
            <div class="form-group row mt-4">
                <label for="inputQuantity" class="col-sm-2 control-label">الكمية</label>
                <div class="col-sm-10">
                    <input type="number" class="form-control @error('quantity') is-invalid @enderror" id="inputQuantity" placeholder="أدخل كمية المنتج" name='quantity' value="{{ old('quantity') }}" min="1" required>
                    @error('quantity')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
            <!-- إضافة حقل اختيار الفئات -->
            <div class="form-group row mt-4">
                <label for="inputCategories" class="col-sm-2 control-label">الفئات</label>
                <div class="col-sm-10">
                    <select  multiple class="form-control select2  @error('categories') is-invalid @enderror" id="inputCategories" name="categories[]" style="width: 100%">
                        @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ (is_array(old('categories')) && in_array($category->id, old('categories'))) ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                        @endforeach
                    </select>
                    @error('categories')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

      <div class="form-group row mt-4">
        <label for="inputImages" class="col-sm-2 control-label">صور المنتج</label>
        <div class="col-sm-10">
          <input type="file" class="form-control-file @error('images') is-invalid @enderror" id="inputImages" name="images[]" multiple accept="image/*" onchange="previewImages(event)">
          @error('images')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
      </div>
            <!-- إضافة قسم لمعاينة الصور -->
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

    function previewImages(event) {
    var previewContainer = document.getElementById('imagePreviewContainer');
    previewContainer.innerHTML = ''; // مسح المعاينات السابقة

    if (event.target.files) {
        [...event.target.files].forEach(file => {
            var reader = new FileReader();
            reader.onload = function(e) {
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
</script>
@endpush


