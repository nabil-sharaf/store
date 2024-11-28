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
                <!-- Previous product details code remains the same until the additional info section -->
                <div class="row">
                    <div class="col-md-6">
                        @if($product->images->count() > 0)
                            <div class="product-image-gallery">
                                <div class="swiper-container">
                                    <div class="swiper-wrapper">
                                        @foreach($product->images as $image)
                                            <div class="swiper-slide">
                                                <img src="{{ asset('storage/' . $image->path) }}"
                                                     alt="{{ $product->name }}">
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
                        <!-- Previous product details remain the same -->

                        <h4 class="text-primary mt-4">التفاصيل</h4>
                        <ul class="list-group">
                            <!-- Previous list items remain the same -->
                        </ul>

                        <h4 class="text-primary">الوصف</h4>
                        <p>{!! $product->description ?? ' لا يوجد ' !!}</p>

                        <h4 class="text-primary text-decoration-underline">معلومات وتفاصيل اضافية:- </h4>
                        <p>{!! $product->info ?? 'لا يوجد' !!}</p>
                    </div>
                </div>

                <!-- New Variants Section -->
                @if($product->variants && $product->variants->count() > 0)
                    <div class="mt-4">
                        <h4 class="text-primary">متغيرات المنتج</h4>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th>الكود</th>
                                    <th>سعر القطاعي</th>
                                    <th>سعر الجملة</th>
                                    <th>الكمية المتاحة</th>
                                    <th>الخصائص</th>
                                    <th>الاجراءات</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($product->variants as $variant)
                                    <tr>
                                        <td>{{ $variant->sku_code }}</td>
                                        <td>{{ $variant->price }} ج</td>
                                        <td>{{ $variant->goomla_price }} ج</td>
                                        <td>{{ $variant->quantity }}</td>
                                        <td>
                                            @if($variant->optionValues)
                                                @foreach($variant->optionValues as $value)
                                                    <span class="badge bg-info me-1">
                                                            {{ $value->option->getTranslation('name','ar') }}: {{ $value->getTranslation('value', 'ar') }}
                                                        </span>
                                                @endforeach
                                            @endif
                                        </td>
                                        <td>
                                            <form action="{{ route('admin.products.variants.delete', ['product' => $product->id, 'variant' => $variant->id]) }}"
                                                  method="POST"
                                                  class="d-inline"
                                                  onsubmit="return confirm('هل أنت متأكد من حذف هذا المتغير؟')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @else
                    <div class="alert alert-info mt-4">
                        لا توجد متغيرات لهذا المنتج
                    </div>
                @endif

                <div class="mt-4">
                    <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-warning">تعديل المنتج</a>
                    <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger"
                                onclick="return confirm('هل أنت متأكد من حذف هذا المنتج؟')">حذف المنتج
                        </button>
                    </form>
                    <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">العودة للقائمة</a>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Previous styles remain the same */
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

        .swiper-button-prev, .swiper-button-next {
            top: 200px;
        }

        /* New styles for variants */
        .badge {
            font-size: 0.85em;
            padding: 5px 8px;
        }

        .table-responsive {
            margin-top: 1rem;
        }

        .table th {
            background-color: #f8f9fa;
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
