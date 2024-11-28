@extends('admin.layouts.app')

@section('page-title')
    popup
@endsection

@section('content')
    <div class="container mt-5">
        <h2 class="mb-4">إدارة الـ popup</h2>

        <!-- عرض الرسائل -->
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <!-- نموذج إدارة البوب أب -->
        <form action="{{ route('admin.popup.update') }}" method="POST" enctype="multipart/form-data" class="row g-4">
            @csrf
            @method('PUT')

            <!-- العنوان -->
            <div class="col-md-6">
                <label for="title" class="form-label">العنوان:</label>
                <input type="text" name="title" value="{{ old('title', $popup?->title ?? '') }}" class="form-control @error('title') is-invalid @enderror" placeholder="أدخل عنوان البوب أب">
                @error('title')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- النص -->
            <div class="col-md-12">
                <label for="popup-text" class="form-label">النص:</label>
                <textarea id='popup-text' name="text" class="form-control @error('text') is-invalid @enderror" rows="4" placeholder="أدخل نص البوب أب">{{ old('text', $popup?->text ?? '') }}</textarea>
                @error('text')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- الصورة -->
            <div class="col-md-6">
                <label for="image" class="form-label">الصورة:</label>
                <input type="file" name="image" class="form-control @error('image') is-invalid @enderror" id="imageInput" onchange="previewImage(event)">
                @error('image')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                @if(isset($popup?->image_path))
                    <img id="imagePreview" src="{{ asset('storage/' . $popup?->image_path) }}" alt="صورة البوب أب" class="img-fluid mt-3" style="max-height: 150px;">
                @else
                    <img id="imagePreview" alt="معاينة الصورة" class="img-fluid mt-3" style="max-height: 150px; display: none;">
                @endif
            </div>

            <!-- نص الزر -->
            <div class="col-md-6">
                <label for="button_text" class="form-label">نص الزر:</label>
                <input type="text" name="button_text" value="{{ old('button_text', $popup?->button_text ?? '') }}" class="form-control @error('button_text') is-invalid @enderror" placeholder="أدخل نص الزر">
                @error('button_text')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- رابط الزر -->
            <div class="col-md-6">
                <label for="button_link" class="form-label">رابط الزر:</label>
                <input type="text" name="button_link" value="{{ old('button_link', $popup?->button_link ?? '') }}" class="form-control @error('button_link') is-invalid @enderror" placeholder="أدخل رابط الزر">
                @error('button_link')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- نص الرابط في الـ Footer -->
            <div class="col-md-6">
                <label for="footer_link_text" class="form-label">نص الرابط في الـ Footer:</label>
                <input type="text" name="footer_link_text" value="{{ old('footer_link_text', $popup?->footer_link_text ?? '') }}" class="form-control @error('footer_link_text') is-invalid @enderror" placeholder="أدخل نص الرابط">
                @error('footer_link_text')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- رابط الـ Footer -->
            <div class="col-md-6">
                <label for="footer_link_url" class="form-label">رابط الـ Footer:</label>
                <input type="text" name="footer_link_url" value="{{ old('footer_link_url', $popup?->footer_link_url ?? '') }}" class="form-control @error('footer_link_url') is-invalid @enderror" placeholder="أدخل رابط الـ Footer">
                @error('footer_link_url')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!--حالة البوب اب -->
            <div class="col-md-6">
                <label for="status" class="form-label">حالة ال popup</label>
                <select class="form-control" name ='status'>
                    <option value="1" {{$popup?->status == 1 ? 'selected' : ''}}>مفعل</option>
                    <option value="0" {{$popup?->status==0 ? 'selected' : ''}}>غير مفعل</option>
                </select>
                @error('status')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- زر الحفظ -->
            <div class="col-12 text-center">
                <button type="submit" class="btn btn-primary mt-4">حفظ التعديلات</button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        function previewImage(event) {
            var imagePreview = document.getElementById('imagePreview');
            var file = event.target.files[0];

            if (file) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    imagePreview.src = e.target.result;
                    imagePreview.style.display = 'block';
                };
                reader.readAsDataURL(file);
            }
        }
    </script>
@endpush
