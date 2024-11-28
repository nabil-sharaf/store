@extends('admin.layouts.app')
@section('content')
    <div class="container">
        <h2 class="mb-4">إدارة صور الموقع</h2>
        <form action="{{ route('admin.settings.update-images') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Logo -->
            <div class="row mb-4">
                <label for="logo" class="col-md-4 col-form-label text-md-right">صورة اللوجو :</label>
                <div class="col-md-6">
                    <img src="{{ asset('storage/'.$siteImages?->logo) }}" alt="Logo" class="img-thumbnail mb-3" id="logo-preview" style="width:100px; max-height: 80px;">
                    <input type="file" name="logo" id="logo" class="form-control @error('logo') is-invalid @enderror" onchange="previewImage(event, 'logo-preview')">
                    @error('logo')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Slider Image -->
            <div class="row mb-4">
                <label for="slider_image" class="col-md-4 col-form-label text-md-right">صورة السلايدر :</label>
                <div class="col-md-6">
                    <img src="{{ asset('storage/'.$siteImages?->slider_image) }}" alt="Slider" class="img-thumbnail mb-3" id="slider-preview" style="width:100px; max-height: 80px;">
                    <input type="file" name="slider_image" id="slider_image" class="form-control @error('slider_image') is-invalid @enderror" onchange="previewImage(event, 'slider-preview')">
                    @error('slider_image')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- offer_one Image -->
            <div class="row mb-4">
                <label for="offer_one" class="col-md-4 col-form-label text-md-right">صورة deal of the day 1 :</label>
                <div class="col-md-6">
                    <img src="{{ asset('storage/'.$siteImages?->offer_one) }}" alt="offer_one" class="img-thumbnail mb-3" id="offer_one-preview" style="width:100px; max-height: 80px;">
                    <input type="file" name="offer_one" id="offer_one" class="form-control @error('offer_one') is-invalid @enderror" onchange="previewImage(event, 'offer_one-preview')">
                    @error('offer_one')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- offer_two Image -->
            <div class="row mb-4">
                <label for="offer_two" class="col-md-4 col-form-label text-md-right">صورة deal of the day 2 :</label>
                <div class="col-md-6">
                    <img src="{{ asset('storage/'.$siteImages?->offer_two) }}" alt="offer_two" class="img-thumbnail mb-3" id="offer_two-preview" style="width:100px; max-height: 80px;">
                    <input type="file" name="offer_two" id="offer_two" class="form-control @error('offer_two') is-invalid @enderror" onchange="previewImage(event, 'offer_two-preview')">
                    @error('offer_two')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- about_us Image -->
            <div class="row mb-4">
                <label for="about_us_image" class="col-md-4 col-form-label text-md-right">صورة صفحة من نحن  :</label>
                <div class="col-md-6">
                    <img src="{{ asset('storage/'.$siteImages?->about_us_image) }}" alt="about_us_image" class="img-thumbnail mb-3" id="about_us_image-preview" style="width:100px; max-height: 80px;">
                    <input type="file" name="about_us_image" id="about_us_image" class="form-control @error('about_us_image') is-invalid @enderror" onchange="previewImage(event, 'about_us_image-preview')">
                    @error('about_us_image')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- sponsors Image -->
            <div class="row mb-4">
                <label for="sponsor_images" class="col-md-4 col-form-label text-md-right">صور الشركات الراعية   :</label>
                <div class="col-md-6">
                    <div id="sponsor-images-preview"></div>
                @if($siteImages && $siteImages->sponsor_images)
                        @foreach($siteImages->sponsor_images as $image)
                            <img src="{{ asset('storage/' . $image) }}" alt="Sponsor Image" class="img-thumbnail" style="display: inline; width:90px; height:60px;">
                        @endforeach
                    @else
                        <p>لايوجد.</p>
                    @endif
                        <input type="file" name="sponsor_images[]" id="sponsor_images" class="form-control @error('sponsor_images') is-invalid @enderror" multiple  onchange="previewMultipleImages(event)">
                    @error('sponsor_images')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Footer Image -->
            <div class="row mb-4">
                <label for="footer_image" class="col-md-4 col-form-label text-md-right">صورة الفوتر :</label>
                <div class="col-md-6">
                    <img src="{{ asset('storage/'.$siteImages?->footer_image) }}" alt="Footer" class="img-thumbnail mb-3" id="footer-preview" style="width:100px; max-height: 80px;">
                    <input type="file" name="footer_image" id="footer_image" class="form-control @error('footer_image') is-invalid @enderror" onchange="previewImage(event, 'footer-preview')">
                    @error('footer_image')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Footer Payment Image -->
            <div class="row mb-4">
                <label for="payment_image" class="col-md-4 col-form-label text-md-right">صورة وسائل الدفع :</label>
                <div class="col-md-6">
                    <img src="{{ asset('storage/'.$siteImages?->payment_image) }}" alt="payment image" class="img-thumbnail mb-3" id="payment_image-preview" style="width:100px; max-height: 80px;">
                    <input type="file" name="payment_image" id="payment_image" class="form-control @error('payment_image') is-invalid @enderror" onchange="previewImage(event, 'payment_image-preview')">
                    @error('payment_image')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Footer car_icon Image -->
            <div class="row mb-4">
                <label for="car_icon" class="col-md-4 col-form-label text-md-right">صورة  العربة الصغيرة :</label>
                <div class="col-md-6">
                    <img src="{{ asset('storage/'.$siteImages?->car_icon) }}" alt="car image" class="img-thumbnail mb-3" id="car_icon-preview" style="width:100px; max-height: 80px;">
                    <input type="file" name="car_icon" id="car_icon" class="form-control @error('car_icon') is-invalid @enderror" onchange="previewImage(event, 'car_icon-preview')">
                    @error('car_icon')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Default Image  -->
            <div class="row mb-4">
                <label for="default_image" class="col-md-4 col-form-label text-md-right">الصورة الاحتياطية    :</label>
                <div class="col-md-6">
                    <img src="{{ asset('storage/'.$siteImages?->default_image) }}" alt="car image" class="img-thumbnail mb-3" id="default_image-preview" style="width:100px; max-height: 80px;">
                    <input type="file" name="default_image" id="default_image" class="form-control @error('default_image') is-invalid @enderror" onchange="previewImage(event, 'default_image-preview')">
                    @error('default_image')
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
        function previewMultipleImages(event) {
            var files = event.target.files;
            var previewContainer = document.getElementById('sponsor-images-preview');

            // Clear any existing previews
            previewContainer.innerHTML = '';

            // Loop over each file and create an img element to preview
            Array.from(files).forEach(function(file) {
                var reader = new FileReader();
                reader.onload = function() {
                    var img = document.createElement('img');
                    img.src = reader.result;
                    img.className = 'img-thumbnail';
                    img.style.width = '75px';
                    img.style.height = '50px';
                    img.style.margin = '3px';
                    previewContainer.appendChild(img);
                }
                reader.readAsDataURL(file);
            });
        }
   </script>
@endpush
