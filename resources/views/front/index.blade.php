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
                                                    <h2>{{ __('home.hero_title') }}</h2>
                                                    <p>{{ __('home.hero_description') }}</p>
                                                    <a href="shop.html" class="btn-theme"><strong>{{ __('home.shop_now') }}</strong></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <img class="thumb-two" src="{{asset('front/assets/img/slider/2.png')}}" alt="Image">
                                <img class="thumb-four" src="{{asset('front/assets/img/photos/3.png')}}" alt="Image">
                            </div>
                            <div class="shape-top bg-img" data-bg-img="{{asset('front/assets/img/photos/1.png')}}"></div>
                            <div class="shape-bottom bg-img" data-bg-img="{{asset('front/assets/img/photos/2.png')}}"></div>
                        </div>
                        <!-- End Slide Item -->
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--== End Hero Area Wrapper ==-->

    <!--== Start Category Area Wrapper ==-->

    <!--== End Category Area Wrapper ==-->

    <!--== Start Product Tab Area Wrapper ==-->
    <section class="product-area product-style1-area">
        <div class="container">
            <div class="row">
                <div class="col-md-6 m-auto">
                    <div class="section-title text-center" data-aos="fade-up" data-aos-duration="1000">
                        <h2 class="title">{{ __('home.products') }}</h2>
                        <div class="desc">
                            <p>{{ __('home.products_description') }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row"">
                <div class="col-md-12">
                    <div class="product-tab-content">
                        <ul class="nav nav-tabs" id="myTab" role="tablist" data-aos="fade-up" data-aos-duration="1000">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="our-features-tab" data-bs-toggle="tab" data-bs-target="#our-features" type="button" role="tab" aria-controls="our-features" aria-selected="true">{{ __('home.all_products') }}</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="best-sellers-tab" data-bs-toggle="tab" data-bs-target="#best-sellers" type="button" role="tab" aria-controls="best-sellers" aria-selected="false">{{ __('home.newly_added') }}</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="new-items-tab" data-bs-toggle="tab" data-bs-target="#new-items" type="button" role="tab" aria-controls="new-items" aria-selected="false">{{ __('home.best_sellers') }}</button>
                            </li>
                        </ul>

                        <div class="tab-content" id="myTabContent" data-aos="fade-up" data-aos-duration="1300">
                            <div class="tab-pane fade show active" id="our-features" role="tabpanel" aria-labelledby="our-features-tab">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="product">
                                            <div class="row">
                                                @foreach($products as $product)
                                                    <div class="col-lg-3 col-md-4 col-sm-6">
                                                        <!-- Start Product Item -->
                                                        <div class="product-item">
                                                            <div class="product-thumb">
                                                                <img src="{{ asset('storage/' . $product->images->first()->path) }}" alt="{{ $product->name }}">

                                                                <div class="product-action">
                                                                    <a class="action-quick-view-cart" href="#" onclick="addToCart(event,{{$product->id}})"><i class="ion-ios-cart"></i></a>

                                                                    <a class="action-quick-view" href="#" data-id="{{ $product->id }}" onclick="showProductDetails(this)"><i class="ion-arrow-expand"></i></a>
                                                                    <a class="action-quick-view-wishlist" href="#" data-id="{{ $product->id }}" onclick="wishListAdd(event,this)"><i class="ion-heart"></i></a>
                                                                </div>
                                                            </div>
                                                            <div class="product-info">
                                                                <div class="rating">
                                                                    <span class="fa fa-star"></span>
                                                                    <span class="fa fa-star"></span>
                                                                    <span class="fa fa-star"></span>
                                                                    <span class="fa fa-star"></span>
                                                                    <span class="fa fa-star"></span>
                                                                </div>
                                                                <h4 class="title"><a href="{{route('product.show',$product->id)}}">{{$product->name}}</a></h4>
                                                                <div class="prices">
                                                                    <span class="price">{{$product->price}} {{__('home.currency')}}</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!-- End Product Item -->
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="best-sellers" role="tabpanel" aria-labelledby="best-sellers-tab">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="product">
                                            <div class="row">
                                                <div class="col-lg-3 col-md-4 col-sm-6">
                                                    <!-- Start Product Item -->
                                                    <div class="product-item">
                                                        <div class="product-thumb">
                                                            <img src="{{asset('front/assets/img/shop/5.png')}}" alt="Image">
                                                            <div class="product-action">
                                                                <a class="action-quick-view" href="shop-cart.html"><i class="ion-ios-cart"></i></a>
                                                                <a class="action-quick-view" href="javascript:void(0)"><i class="ion-arrow-expand"></i></a>
                                                                <a class="action-quick-view-wishlist" href="shop-wishlist.html"><i class="ion-heart"></i></a>
                                                                <a class="action-quick-view" href="shop-quickview.html"><i class="ion-search"></i></a>
                                                            </div>
                                                        </div>
                                                        <div class="product-info">
                                                            <div class="rating">
                                                                <span class="fa fa-star"></span>
                                                                <span class="fa fa-star"></span>
                                                                <span class="fa fa-star"></span>
                                                                <span class="fa fa-star"></span>
                                                                <span class="fa fa-star"></span>
                                                            </div>
                                                            <h4 class="title"><a href="shop-single-product.html">Royal Black</a></h4>
                                                            <div class="prices">
                                                                <span class="price">$19.00</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- End Product Item -->
                                                </div>
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
                                                <div class="col-lg-3 col-md-4 col-sm-6">
                                                    <!-- Start Product Item -->
                                                    <div class="product-item">
                                                        <div class="product-thumb">
                                                            <img src="{{asset('front/assets/img/shop/6.png')}}" alt="Image">
                                                            <div class="product-action">
                                                                <a class="action-quick-view" href="shop-cart.html"><i class="ion-ios-cart"></i></a>
                                                                <a class="action-quick-view" href="javascript:void(0)"><i class="ion-arrow-expand"></i></a>
                                                                <a class="action-quick-view-wishlist" href="shop-wishlist.html"><i class="ion-heart"></i></a>
                                                                <a class="action-quick-view" href="shop-quickview.html"><i class="ion-search"></i></a>
                                                            </div>
                                                        </div>
                                                        <div class="product-info">
                                                            <div class="rating">
                                                                <span class="fa fa-star"></span>
                                                                <span class="fa fa-star"></span>
                                                                <span class="fa fa-star"></span>
                                                                <span class="fa fa-star"></span>
                                                                <span class="fa fa-star"></span>
                                                            </div>
                                                            <h4 class="title"><a href="shop-single-product.html">Modern Chair</a></h4>
                                                            <div class="prices">
                                                                <span class="price">$30.00</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- End Product Item -->
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
        </div>
    </section>
    <!--== End Product Tab Area Wrapper ==-->

@endsection


@push('styles')
    <style>

    .slider-content-area {
        background-color: #c2e0eb; /* اختر لون الخلفية المطلوب */
        /*max-height: 650px;*/

    }
    </style>

@endpush
