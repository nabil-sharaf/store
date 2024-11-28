@extends('front.layouts.app')
@section('title','الرئيسية')
@section('content')

    <!--== Start Hero Area Wrapper ==-->
    <section class="home-slider-area slider-default">
        <div class="home-slider-content">
            <div class="swiper-container home-slider-container">
                <div class="swiper-wrapper">
                    <div class="swiper-slide">
                        <!-- Start Slide Item -->
                        <div class="home-slider-item">
                            <div class="slider-content-area">
                                <div class="container">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="content">
                                                <div class="inner-content text-center" style="direction: rtl">
                                                    <h2>{!! __('home.hero_title') !!}  </h2>
                                                    <p>{!!  __('home.hero_description') !!} </p>
                                                    <a href="{{route('categories.index')}}"
                                                       class="btn-theme"><strong>{{ __('home.shop_now') }}</strong></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <img class="thumb-two"
                                     src="{{asset('storage').'/'.($siteImages?->slider_image ?: $siteImages?->default_image)}}"
                                     alt="Image">
                                <img class="thumb-four"
                                     src="{{asset('storage').'/'.($siteImages?->car_icon ?: $siteImages?->default_image)}}"
                                     alt="Image">
                            </div>
                            <div class="shape-top bg-img"
                                 data-bg-img="{{asset('front/assets/img/photos/1.png')}}"></div>
                            <div class="shape-bottom bg-img"
                                 data-bg-img="{{asset('front/assets/img/photos/2.png')}}"></div>
                        </div>
                        <!-- End Slide Item -->
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--== End Hero Area Wrapper ==-->

    <!--== Start Category Area Wrapper ==-->
    <section class="category-area product-category2-area" data-aos="fade-up" data-aos-duration="1000">
        <div class="container">
            <div class="row category-items2">
                @foreach($categories as $cat)
                    <div class="col-6 col-md-6">
                        <div class="category-item">
                            <div class="thumb">
                                <img class="w-100"
                                     src="{{asset('storage/'.($cat->image?:$siteImages?->default_image))}}" alt="Image">
                                <div class="content">
                                    <div class="contact-info">
                                        <h3 class="title text-white">{{$cat->name}}</h3>
                                        <h4 class="price text-white">{!! $cat->description !!}</h4>
                                    </div>
                                    <a class="btn-theme"
                                       href="{{route('category.show',$cat->id)}}">{{__('home.shop_now')}}</a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    <!--== End Category Area Wrapper ==-->

    <!--== Start Deal of Day Area Wrapper ==-->
    <section class="divider-area divider-style1-area bg-img" data-bg-img={{asset('front/assets')}}/img/divider/bg1.png"
             data-aos="fade-up" data-aos-duration="1000">
        <div class="container position-relative">
            <div class="row">
                <div class="col-lg-6 m-auto">
                    <div class="divider-content">
                        <h2 class="title">{!! __('home.deal_of_day_subject') !!}</h2>
                        <div>{!! __('home.deal_of_day_desc') !!}</div>
                        <div class="countdown-content">
                            <ul class="countdown-timer">
                                <li><span class="days">00</span>
                                    <p class="days_text">Days</p></li>
                                <li><span class="hours">00</span>
                                    <p class="hours_text">Hours</p></li>
                                <li><span class="minutes">00</span>
                                    <p class="minutes_text">MINUTES</p></li>
                                <li><span class="seconds">00</span>
                                    <p class="seconds_text">SECONDS</p></li>
                            </ul>
                        </div>
                        <a class="btn-theme" href="{{route('products.offers')}}">{{__('home.shop_now')}}</a>
                    </div>
                </div>
            </div>
            <div class="shape-group divider-image">
                <div class="shape-style3 ">
                    <img src="{{asset('storage').'/'.($siteImages?->offer_two ?:$siteImages?->default_image )}}"
                         alt="Image">
                </div>
                <div class="shape-style4">
                    <img src="{{asset('storage').'/'.($siteImages?->offer_one ?:$siteImages?->default_image)}}"
                         alt="Image">
                </div>
            </div>
        </div>
        <div class="shape-group">
            <div class="shape-style1" data-bg-img="{{asset('front/assets')}}/img/divider/shape1.png"></div>
            <div class="shape-style2" data-bg-img="{{asset('front/assets')}}/img/divider/shape2.png"></div>
        </div>
    </section>
    <!--== End DEal of day Area Wrapper ==-->

    <!--== Start Products Area ==-->
    <section class="product-area product-style1-area">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="product-tab-content">
                        <ul class="nav nav-tabs" id="myTab" role="tablist" data-aos="fade-up" data-aos-duration="1000">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="new-items-tab" data-bs-toggle="tab"
                                        data-bs-target="#new-items" type="button" role="tab"
                                        aria-controls="new-items"
                                        aria-selected="true">{{ __('home.newly_added') }}</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="best-sellers-tab" data-bs-toggle="tab"
                                        data-bs-target="#best-sellers" type="button" role="tab"
                                        aria-controls="best-sellers"
                                        aria-selected="false">{{ __('home.best_sellers') }}</button>
                            </li>
                        </ul>

                        <div class="tab-content" id="myTabContent" data-aos="fade-up" data-aos-duration="1300">
                            <div class="tab-pane fade show active" id="our-features" role="tabpanel"
                                 aria-labelledby="our-features-tab">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="product">
                                            <div class="row">
                                                @foreach($newProducts as $product)
                                                    <div class="col-lg-3 col-md-4 col-6">
                                                        <!-- Start Product Item -->
                                                        <x-product-item :product="$product"/>
                                                        <!-- End Product Item -->
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="best-sellers" role="tabpanel"
                                 aria-labelledby="best-sellers-tab">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="product">
                                            <div class="row">
                                                @foreach($bestProducts as $product)
                                                    <div class="col-lg-3 col-md-4 col-6">
                                                        <!-- Start Product Item -->
                                                        <x-product-item :product="$product"/>
                                                        <!-- End Product Item -->
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="new-items" role="tabpanel" aria-labelledby="new-items-tab">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="product">
                                            <div class="row">
                                                @foreach($newProducts as $product)
                                                    <div class="col-lg-3 col-md-4 col-6">
                                                        <!-- Start Product Item -->
                                                        <x-product-item :product="$product"/>
                                                        <!-- End Product Item -->
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--== End  Products ==-->

    <!--== Start Trending Products Area ==-->
    <section class="product-area product-style2-area trending-products-section">
        <div class="container">
            <div class="row">
                <div class="col-md-6 m-auto">
                    <div class="section-title text-center" data-aos="fade-up" data-aos-duration="1000">
                        <h2 class="title">{!! __('home.Trending_products_subject') !!}</h2>
                        <div class="desc">
                            {!! __('home.Trending_products_desc') !!}
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="product-tab1-slider" data-aos="fade-up" data-aos-duration="1500">
                        @foreach($trendingProducts as $product)
                            <div class="slide-item">
                                <!-- Start Product Item -->
                                <x-product-item :product="$product"/>
                                <!-- End Product Item -->
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--== End Trending Products Area ==-->

@endsection


@push('styles')
    <style>

        .product-item {
            position: relative; /* يضمن أن العناصر المطلقة داخله تظل ضمن حدوده */
            overflow: hidden; /* يمنع خروج أي محتوى زائد عن حدوده */
        }

        .slider-content-area {
            background-color: #c2e0eb; /* اختر لون الخلفية المطلوب */
            /*max-height: 650px;*/

        }


        .custom-title {
            font-family: 'Cairo', sans-serif; /* تغيير الخط */
            font-size: 24px; /* حجم الخط */
            color: #f379a7; /* لون النص */
        }

        .custom-popup {
            border-radius: 15px; /* شكل الحواف */
            border: 1px solid #f379a7; /* إضافة إطار حول النافذة */
        }

        .custom-footer a {
            font-weight: bold;
            text-decoration: underline;
        }

        .custom-confirm-btn {
            border: 2px solid #f379a7; /* البوردر حول زر التأكيد */
            border-radius: 8px; /* زاوية مستديرة للزر */
            padding: 10px 20px; /* تعديل مساحة الزر */
            background-color: #f379a7; /* لون الخلفية */
            color: white; /* لون النص */
            font-weight: bold;
            transition: 0.3s; /* تأثيرات عند التمرير */
        }

        .custom-confirm-btn:hover {
            background-color: #f379a7; /* لون الخلفية عند التمرير */
            border-color: #f379a7; /* لون البوردر عند التمرير */
        }

        div:where(.swal2-icon) {
            border: 0px solid #f379a7; /* البوردر حول الصورة */
            border-radius: 50%; /* زاوية دائرية للصورة */
            padding: 5px; /* مسافة داخلية حول الصورة */
            /*box-shadow: 0 0 10px rgba(0, 0, 0, 0.2); !* ظل حول الصورة *!*/
            height: 90px;
            width: 250px
        }

        div:where(.swal2-container) button:where(.swal2-styled):not([disabled]):focus {
            outline: none !important; /* إزالة الـ default outline */
            border: 0; /* لون البوردر عند التركيز (focus) */
            box-shadow: 0 0 3px rgb(228, 107, 255) !important; /* إضافة تأثير ظل */
        }

        .pop-up-button {
            text-decoration: none;
            color: white;
        }

        .pop-up-button:hover {
            color: whitesmoke;
        }

        /*variants*/
    </style>
@endpush

@push('scripts')
    <script>
        @if(isset($popup) && $popup->status==1)

        @if(!session()->has('popup_show'))
        document.addEventListener('DOMContentLoaded', function () {
            Swal.fire({
                title: '{{ $popup->title }}',
                html: '{!! $popup->text !!} ',
                iconHtml: '<img src="{{ asset('storage/' . ($popup->image_path?:$siteImages?->default_image)) }}" style="width:200px; max-height:100px;" />',
                background: '#f0f0f0',
                color: '#333333',
                confirmButtonText: '<a class="pop-up-button" href="{{ $popup->button_link }}">{{ $popup->button_text }}</a>',
                confirmButtonColor: '#f379a7',
                footer: '<a href="{{ $popup->footer_link_url }}" style="color: #f379a7;">{{ $popup->footer_link_text }}</a>',
                customClass: {
                    title: 'custom-title',
                    popup: 'custom-popup',
                    footer: 'custom-footer'
                }
            });
        });
            <?php session()->put('popup_show', true); ?>
        @endif
        @endif

        $(document).ready(function () {
            // التأكد من وجود العنصر
            if ($('.product-tab1-slider').length > 0) {
                $('.slick-prev, .slick-next').css('display', 'block');
                $('.product-tab1-slider').slick({
                    arrows: true,
                    dots: true,        // إضافة النقاط للتنقل
                    infinite: true,    // تمكين التمرير الدائري
                    slidesToShow: 1,   // عدد الشرائح المعروضة
                    slidesToScroll: 1  // عدد الشرائح المتحركة
                });
            } else {
                console.warn('لم يتم العثور على السلايدر');
            }
        });
    </script>

@endpush
