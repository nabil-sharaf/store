@extends('admin.layouts.app')

@section('page-title')
    تفاصيل المنتج: {{ $product->name }}
@endsection

@section('content')
    <div class="container mt-4">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h2 class="mb-0">{{ $product->name }}</h2>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        @if($product->images->count() > 0)
                            <div class="product-image-gallery">
                                <div class="swiper-container">
                                    <div class="swiper-wrapper">
                                        @foreach($product->images as $image)
                                            <div class="swiper-slide">
                                                <img src="{{ asset('storage/' . $image->path) }}" alt="{{ $product->name }}">
                                            </div>
                                        @endforeach
                                    </div>
                                    <div class="swiper-pagination"></div>
                                    <div class="swiper-button-next"></div>
                                    <div class="swiper-button-prev"></div>
                                </div>
                            </div>
                        @else
                            <div class="alert alert-info">لا توجد صور للمنتج</div>
                        @endif
                    </div>
                    <div class="col-md-6">
                        <h4 class="text-primary">الوصف</h4>
                        <p>{{ $product->description }}</p>

                        <h4 class="text-primary mt-4">التفاصيل</h4>
                        <ul class="list-group">
                            <li class="list-group-item"><strong>السعر:</strong> {{ $product->price }} ريال</li>
                            <li class="list-group-item"><strong>الكمية المتاحة:</strong> {{ $product->quantity }}</li>
                            <li class="list-group-item"><strong>القسم:</strong> {{ $product->categories->pluck('name')->implode(', ') }}</li>

                            <!-- الحقول الجديدة -->
                            <li class="list-group-item"><strong>قيمة الخصم:</strong> {{ $product->productDiscount->discount }} {{ $product->productDiscount->discount_type == 'fixed' ? 'ريال' : '%' }}</li>
                            <li class="list-group-item"><strong>نوع الخصم:</strong> {{ $product->productDiscount->discount_type == 'fixed' ? 'ثابت' : 'نسبة مئوية' }}</li>
                            <li class="list-group-item"><strong>تاريخ البدء:</strong> {{ $product->productDiscount->start_date ? $product->productDiscount->start_date->format('Y-m-d') : 'لا يوجد' }}</li>
                            <li class="list-group-item"><strong>تاريخ الانتهاء:</strong> {{ $product->productDiscount->end_date ? $product->productDiscount->end_date->format('Y-m-d') : 'لا يوجد' }}</li>
                        </ul>
                    </div>
                </div>

                <div class="mt-4">
                    <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-warning">تعديل</a>
                    <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" onclick="return confirm('هل أنت متأكد من حذف هذا المنتج؟')">حذف</button>
                    </form>
                    <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">العودة للقائمة</a>
                </div>
            </div>
        </div>
    </div>

    <style>
        .product-image-gallery {
            width: 100%;
            height: 400px;
            background-color: #f8f9fa;
            border-radius: 8px;
            overflow: hidden;
        }
        .swiper-container {
            width: 100%;
            height: 100%;
        }
        .swiper-slide {
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .swiper-slide img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
        }
    </style>

@endsection

@push('scripts')
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            new Swiper('.swiper-container', {
                loop: true,
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true,
                },
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                },
            });
        });
    </script>
@endpush
