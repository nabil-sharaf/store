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
                    <div class="single-product-thumb">
                        <div class="swiper-container single-product-thumb-slider">
                            <div class="swiper-wrapper">
                                @foreach($product->images as $image)
                                    <div class="swiper-slide zoom zoom-hover ">
                                        <div class="thumb-item">
                                            <a class="lightbox-image" data-fancybox="gallery" href="{{asset('storage/'.$image->path)}}">
                                                <img class="lazyload" data-src="{{asset('storage/'.$image->path)}}" alt="Image-HasTech">
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="single-product-nav">
                        <div class="swiper-container single-product-nav-slider">
                            <div class="swiper-wrapper">
                                @foreach($product->images as $image)
                                    <div class="swiper-slide">
                                        <div class="nav-item">
                                            <img class="lazyload" data-src="{{asset('storage/'.$image->path)}}" alt="Image-HasTech">
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="single-product-info">
                    <h4 class="title"> {{$product->name}}</h4>
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
<!-- Include Swiper.js and LazySizes -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/lazysizes/5.3.2/lazysizes.min.js" async></script>
<script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>

<script>
    var swiperNav = new Swiper('.single-product-nav-slider', {
        slidesPerView: 4,
        spaceBetween: 10,
        freeMode: true,
        watchSlidesVisibility: true,
        watchSlidesProgress: true,
        breakpoints: {
            640: {
                slidesPerView: 2,
                spaceBetween: 5,
            },
            768: {
                slidesPerView: 3,
                spaceBetween: 7,
            },
            1024: {
                slidesPerView: 4,
                spaceBetween: 10,
            },
        }
    });

    var swiperThumb = new Swiper('.single-product-thumb-slider', {
        slidesPerView: 1,
        spaceBetween: 10,
        loop: true,
        grabCursor: true,
        touchReleaseOnEdges: true,
        passiveListeners: true,
        threshold: 10,
        allowTouchMove: true, // للسماح بالسحب الأفقي
        touchStartPreventDefault: false, // لتعطيل منع التمرير
        thumbs: {
            swiper: swiperNav,
        },
        on: {
            touchMove: function(swiper, event) {
                if (Math.abs(event.velocityY) > Math.abs(event.velocityX)) {
                    swiper.allowTouchMove = false; // تعطيل السحب الأفقي إذا كان التمرير عموديًا
                } else {
                    swiper.allowTouchMove = true; // إعادة تفعيل السحب الأفقي إذا كان السحب أفقيًا
                }
            }
        }
    });
    $('[data-fancybox="gallery"]').fancybox({
        protect: true, // لحماية الصورة من الحفظ
        buttons: [
            "zoom",      // زر التكبير
            "close"      // زر الإغلاق
        ],
        image: {
            preload: true, // تحميل الصور مسبقاً لتحسين الأداء
        },
        zoomOpacity: true,  // تمكين التكبير مع تحريك الصورة
        transitionEffect: "fade", // تأثير عند الانتقال
        thumbs: {
            autoStart: true  // عرض الصور المصغرة عند فتح الصور
        },
        afterLoad: function(instance, current) {
            // التأكد من أن الصورة تعرض بالحجم الكامل
            var img = current.$image;
            img.css('width', '100%');  // ضبط العرض الكامل
            img.css('max-width', 'none'); // تعطيل الحد الأقصى للعرض
            img.css('height', 'auto');  // السماح بالتمدد العمودي حسب النسبة
        }
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
    <style>

    .single-product-info .prices span{
        font-size: 20px !important;
    }
    .product-description-review .tab-content .product-desc{
        text-align: unset;
    }

        /* تحسين العرض على الشاشات الصغيرة */
    .lazyload {
        opacity: 0;
        transition: opacity 0.3s;
    }

    .lazyloaded {
        opacity: 1;
    }
    </style>
@endpush
