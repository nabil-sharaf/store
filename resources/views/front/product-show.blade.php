@extends('front.layouts.app')
@section('title','تفاصيل المنج')
@section('content')
    <!--== Start Page Title Area ==-->
    <section class="page-title-area">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-12 m-auto">
                    <div class="page-title-content text-center">
                        <h2 class="title">تفاصيل المنتج</h2>
                        <div class="bread-crumbs"><a href="{{route('home.index')}}"> {{__('home.title')}} </a><span class="breadcrumb-sep"> // </span><span class="active">اسم المنتج</span></div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--== End Page Title Area ==-->

    <!--== Start Shop Area ==-->
    <section class="product-single-area">
        <div class="container">
            <div class="row">
                <div class="col-sm-12 col-md-8 offset-md-2 col-lg-6 offset-lg-0">
                    <div class="single-product-slider">
                        <div class="swiper-container single-product-thumb-slider">
                            <div class="swiper-wrapper">
                                @foreach($product->images as $image)
                                    <div class="swiper-slide">
                                        <div class="thumb-item">
                                            <a href="{{asset('storage/'.$image->path)}}" data-fancybox="gallery">
                                                <img src="{{asset('storage/'.$image->path)}}" alt="Image-HasTech">
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="swiper-pagination"></div>
                            <div class="swiper-button-next"></div>
                            <div class="swiper-button-prev"></div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="single-product-info">
                        <h4 class="title">{{$product->name}}</h4>
                        <div class="prices">
                            <x-product-price :productPrice="$product->product_price" :discountedPrice="$product->discounted_price" />
                        </div>
                        <div class="product-rating">
                            <div class="rating">
                                <span class="fa fa-star"></span>
                                <span class="fa fa-star"></span>
                                <span class="fa fa-star"></span>
                                <span class="fa fa-star"></span>
                                <span class="fa fa-star"></span>
                            </div>
                        </div>
                        <div class="quick-product-action">
                            <div class="action-top">
                                <div class="pro-qty">
                                    <input type="text" id="quantity_{{$product->id}}" title="{{ __('aside_menu.quantity') }}" value="1" />
                                </div>
                                <button class="btn btn-theme font-weight-bold" onclick="addToCart(event, {{$product->id}}, document.getElementById('quantity_{{$product->id}}').value)">
                                    {{ __('aside_menu.add_to_cart') }}
                                </button>
                                <a class="btn-wishlist" href="" data-id="{{ $product->id }}" onclick="wishListAdd(event,this)">
                                    {{ __('aside_menu.add_to_wishlist') }}
                                </a>
                            </div>
                        </div>
                        <div class="widget">
                            <h3 class="title">{{__('aside_menu.categories')}}:</h3>
                            <div class="widget-tags">
                                @foreach($product->categories as $cat)
                                    <a href="{{route('category.show',$cat->id)}}">{{$cat->name}} .  </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <div class="product-description-review">
                            <ul class="nav nav-tabs product-description-tab-menu" id="myTab" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="product-aditional-tab" data-bs-toggle="tab" data-bs-target="#commentProduct" type="button" role="tab" aria-selected="false">{{ __('aside_menu.information') }}</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="product-desc-tab" data-bs-toggle="tab" data-bs-target="#productDesc" type="button" role="tab" aria-controls="productDesc" aria-selected="true">{{ __('aside_menu.product_description') }}</button>
                                </li>
                            </ul>
                            <div class="tab-content product-description-tab-content" id="myTabContent">
                                <div class="tab-pane fade" id="commentProduct" role="tabpanel" aria-labelledby="product-aditional-tab">
                                    <div class="product-desc">
                                        <p> {!!  $product->info !!}</p>
                                    </div>
                                </div>
                                <div class="tab-pane fade show active" id="productDesc" role="tabpanel" aria-labelledby="product-desc-tab">
                                    <div class="product-desc">
                                        <p>{!! $product->description !!}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--== End Shop Area ==-->


@endsection
@push('scripts')


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var mySwiper = new Swiper('.single-product-thumb-slider', {
                slidesPerView: 1,
                spaceBetween: 10,
                centeredSlides: true,
                loop: true,
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                },
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true,
                },
                breakpoints: {
                    992: {
                        slidesPerView: 1.5,
                        spaceBetween: 30,
                    }
                }
            });

            // تكبير الصور باستخدام Fancybox
            Fancybox.bind("[data-fancybox]", {
                // يمكنك إضافة خيارات Fancybox هنا إذا لزم الأمر
            });
        });
    </script>

    <script>
    $(document).ready(function() {
        $('[data-bs-toggle="tab"]').on('click', function(e) {
            e.preventDefault(); // منع السلوك الافتراضي

            var targetId = $(this).data('bs-target'); // الحصول على الهدف
            var $targetContent = $(targetId); // الحصول على محتوى التاب

            if ($targetContent.length) {
                // إزالة الفئات 'show' و 'active' من التاب النشط حاليًا
                $('.tab-pane.show.active').removeClass('show active');

                // إضافة الفئات 'show' و 'active' إلى التاب الجديد
                $targetContent.addClass('show active');
            }
        });
    });
    </script>
@endpush

@push('styles')

    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@4.0/dist/fancybox.css" />
    <script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@4.0/dist/fancybox.umd.js"></script>


    <style>

        /* أنماط عرض الصور */
        .single-product-slider {
            width: 100%;
            max-width: 100%;
            margin: 0 auto;
        }
        .swiper-container {
            width: 100%;
            height: 300px;
        }
        .swiper-slide {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100%;
            height: 100%;
        }
        .thumb-item {
            width: 100%;
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .thumb-item img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
        }

        /* تعديلات للشاشات المتوسطة والكبيرة */
        @media (min-width: 768px) {
            .swiper-container {
                height: 400px;
            }
        }

        @media (min-width: 992px) {
            .swiper-container {
                height: 500px;
            }
            .swiper-slide {
                width: 80%;
            }
        }

        /* أنماط معلومات المنتج */
        .single-product-info {
            margin-top: 20px;
        }
        .single-product-info .title {
            font-size: 24px;
            margin-bottom: 10px;
        }
        .prices {
            font-size: 18px;
            margin-bottom: 15px;
        }
        .product-rating {
            margin-bottom: 15px;
        }
        .quick-product-action {
            margin-bottom: 20px;
        }
        .widget-tags a {
            display: inline-block;
            margin-right: 5px;
            margin-bottom: 5px;
            padding: 5px 10px;
            background-color: #f1f1f1;
            text-decoration: none;
            color: #333;
        }

        /* أنماط التبويبات */
        .nav-tabs {
            border-bottom: 1px solid #dee2e6;
            padding-left: 0;
        }
        .nav-tabs .nav-item {
            margin-bottom: -1px;
        }
        .nav-tabs .nav-link {
            border: 1px solid transparent;
            border-top-left-radius: 0.25rem;
            border-top-right-radius: 0.25rem;
            padding: 0.5rem 1rem;
            text-decoration: none;
            color: #495057;
        }
        .nav-tabs .nav-link.active {
            color: #495057;
            background-color: #fff;
            border-color: #dee2e6 #dee2e6 #fff;
        }
        .tab-content {
            padding: 20px 0;
        }

    </style>
@endpush
