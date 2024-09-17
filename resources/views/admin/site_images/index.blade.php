@extends('admin.layouts.app')
@section('content')
    <div class="container">
        <h2 class="mb-4">إدارة صور الموقع</h2>
        <form action="{{ route('admin.settings.update-images') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Logo -->
            <div class="row mb-4">
                <label for="logo" class="col-md-4 col-form-label text-md-right">صورة اللوجو الحالية:</label>
                <div class="col-md-6">
                    <img src="{{ asset('storage/'.$siteImages?->logo) }}" alt="Logo" class="img-thumbnail mb-3" id="logo-preview" width="150">
                    <input type="file" name="logo" id="logo" class="form-control @error('logo') is-invalid @enderror" onchange="previewImage(event, 'logo-preview')">
                    @error('logo')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Slider Image -->
            <div class="row mb-4">
                <label for="slider_image" class="col-md-4 col-form-label text-md-right">صورة السلايدر الحالية:</label>
                <div class="col-md-6">
                    <img src="{{ asset('storage/'.$siteImages?->slider_image) }}" alt="Slider" class="img-thumbnail mb-3" id="slider-preview" width="150">
                    <input type="file" name="slider_image" id="slider_image" class="form-control @error('slider_image') is-invalid @enderror" onchange="previewImage(event, 'slider-preview')">
                    @error('slider_image')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- offer_one Image -->
            <div class="row mb-4">
                <label for="offer_one" class="col-md-4 col-form-label text-md-right">صورة العروض 1 :</label>
                <div class="col-md-6">
                    <img src="{{ asset('storage/'.$siteImages?->offer_one) }}" alt="offer_one" class="img-thumbnail mb-3" id="offer_one-preview" width="150">
                    <input type="file" name="offer_one" id="offer_one" class="form-control @error('offer_one') is-invalid @enderror" onchange="previewImage(event, 'offer_one-preview')">
                    @error('offer_one')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- offer_two Image -->
            <div class="row mb-4">
                <label for="offer_two" class="col-md-4 col-form-label text-md-right">صورة العروض 2 :</label>
                <div class="col-md-6">
                    <img src="{{ asset('storage/'.$siteImages?->offer_two) }}" alt="offer_two" class="img-thumbnail mb-3" id="offer_two-preview" width="150">
                    <input type="file" name="offer_two" id="offer_two" class="form-control @error('offer_two') is-invalid @enderror" onchange="previewImage(event, 'offer_two-preview')">
                    @error('offer_two')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Footer Image -->
            <div class="row mb-4">
                <label for="footer_image" class="col-md-4 col-form-label text-md-right">صورة الفوتر الحالية:</label>
                <div class="col-md-6">
                    <img src="{{ asset('storage/'.$siteImages?->footer_image) }}" alt="Footer" class="img-thumbnail mb-3" id="footer-preview" width="150">
                    <input type="file" name="footer_image" id="footer_image" class="form-control @error('footer_image') is-invalid @enderror" onchange="previewImage(event, 'footer-preview')">
                    @error('footer_image')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Footer Payment Image -->
            <div class="row mb-4">
                <label for="payment_image" class="col-md-4 col-form-label text-md-right">صورة وسائل الدفع الحالية:</label>
                <div class="col-md-6">
                    <img src="{{ asset('storage/'.$siteImages?->payment_image) }}" alt="payment image" class="img-thumbnail mb-3" id="payment_image-preview" width="150">
                    <input type="file" name="payment_image" id="footer_image" class="form-control @error('payment_image') is-invalid @enderror" onchange="previewImage(event, 'payment_image-preview')">
                    @error('payment_image')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Submit Button -->
            <div class="row mb-0">
                <div class="col-md-8 offset-md-4">
                    <button type="submit" class="btn btn-primary">تحديث الصور</button>
                </div>
            </div>
        </form>
    </div>
@endsection
@push('scripts')
    <script>
        function previewImage(event, previewId) {
            var reader = new FileReader();
            reader.onload = function() {
                var output = document.getElementById(previewId);
                output.src = reader.result;
            }
            reader.readAsDataURL(event.target.files[0]);
        }
   </script>
@endpush
