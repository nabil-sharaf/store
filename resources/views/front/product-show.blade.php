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
                                                <img src="{{asset('storage/'.$image->path)}}" alt="Image-HasTech">
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
                                    <div class="swiper-slide" >
                                        <div class="nav-item">
                                            <img src="{{asset('storage/'.$image->path)}}" alt="Image-HasTech">
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
                            <span class="price">{{$product->price . __('home.currency')}}</span>
                        </div>
                        <div class="product-rating">
                            <div class="rating">
                                <span class="fa fa-star"></span>
                                <span class="fa fa-star"></span>
                                <span class="fa fa-star"></span>
                                <span class="fa fa-star"></span>
                                <span class="fa fa-star"></span>
                            </div>
                            <div class="review">
                                <a href="#">( 5 Customer Review )</a>
                            </div>
                        </div>
                        <div class="single-product-featured">
                            <ul>
                                <li><i class="fa fa-check"></i> {{ __('aside_menu.free_shipping') }}</li>
                                <li><i class="fa fa-check"></i> {{ __('aside_menu.support_247') }}</li>
                                <li><i class="fa fa-check"></i> {{ __('aside_menu.money_return') }}</li>
                            </ul>
                        </div>

                        <p class="product-desc">{{ __('aside_menu.product_description') }}</p>
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
                            </div>                        </div>
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
{{--                                <li class="nav-item" role="presentation">--}}
{{--                                    <button class="nav-link" id="product-review-tab" data-bs-toggle="tab" data-bs-target="#productReview" type="button" role="tab" aria-controls="productReview" aria-selected="false">{{ __('aside_menu.reviews') }} (0)</button>--}}
{{--                                </li>--}}
                            </ul>
                            <div class="tab-content product-description-tab-content" id="myTabContent">
                                <div class="tab-pane fade" id="commentProduct" role="tabpanel" aria-labelledby="product-aditional-tab">
                                    <div class="product-desc">
                                        <p> {{$product->description}}</p>
                                    </div>
                                </div>
                                <div class="tab-pane fade show active" id="productDesc" role="tabpanel" aria-labelledby="product-desc-tab">
                                    <div class="product-desc">
                                        <p>{{$product->description??'لا يوجد مواصفات للمنتج حاليا'}}</p>
                                    </div>
                                </div>
{{--                                <div class="tab-pane fade" id="productReview" role="tabpanel" aria-labelledby="product-review-tab">--}}
{{--                                    <div class="product-review">--}}
{{--                                        <div class="review-header">--}}
{{--                                            <h4 class="title">Customer Reviews</h4>--}}
{{--                                            <div class="review-info">--}}
{{--                                                <ul class="review-rating">--}}
{{--                                                    <li><i class="fa fa-star"></i></li>--}}
{{--                                                    <li><i class="fa fa-star"></i></li>--}}
{{--                                                    <li><i class="fa fa-star"></i></li>--}}
{{--                                                    <li><i class="fa fa-star"></i></li>--}}
{{--                                                    <li><i class="fa fa-star-o"></i></li>--}}
{{--                                                </ul>--}}
{{--                                                <span class="review-caption">Based on 1 review</span>--}}
{{--                                                <span class="review-write-btn">Write a review</span>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                        <div class="product-review-form">--}}
{{--                                            <h4 class="title">Write a review</h4>--}}
{{--                                            <form action="#" method="post">--}}
{{--                                                <div class="review-form-content">--}}
{{--                                                    <div class="row">--}}
{{--                                                        <div class="col-md-12">--}}
{{--                                                            <div class="form-group">--}}
{{--                                                                <label for="reviewFormName">Name</label>--}}
{{--                                                                <input class="form-control" id="reviewFormName" type="text" placeholder="Enter your name" required="">--}}
{{--                                                            </div>--}}
{{--                                                        </div>--}}
{{--                                                        <div class="col-md-12">--}}
{{--                                                            <div class="form-group">--}}
{{--                                                                <label for="reviewFormEmail">Email</label>--}}
{{--                                                                <input class="form-control" id="reviewFormEmail" type="email" placeholder="john.smith@example.com" required="">--}}
{{--                                                            </div>--}}
{{--                                                        </div>--}}
{{--                                                        <div class="col-md-12">--}}
{{--                                                            <div class="rating">--}}
{{--                                                                <span class="rating-title">Rating</span>--}}
{{--                                                                <span>--}}
{{--                                    <a class="fa fa-star-o" href="#/"></a>--}}
{{--                                    <a class="fa fa-star-o" href="#/"></a>--}}
{{--                                    <a class="fa fa-star-o" href="#/"></a>--}}
{{--                                    <a class="fa fa-star-o" href="#/"></a>--}}
{{--                                    <a class="fa fa-star-o" href="#/"></a>--}}
{{--                                  </span>--}}
{{--                                                            </div>--}}
{{--                                                        </div>--}}
{{--                                                        <div class="col-md-12">--}}
{{--                                                            <div class="form-group">--}}
{{--                                                                <label for="reviewReviewTitle">Review Title</label>--}}
{{--                                                                <input class="form-control" id="reviewReviewTitle" type="text" placeholder="Give your review a title" required="">--}}
{{--                                                            </div>--}}
{{--                                                        </div>--}}
{{--                                                        <div class="col-md-12">--}}
{{--                                                            <div class="form-group">--}}
{{--                                                                <label for="reviewFormTextarea">Body of Review <span>(1500)</span></label>--}}
{{--                                                                <textarea class="form-control textarea" id="reviewFormTextarea" name="comment" rows="7" placeholder="Write your comments here" required=""></textarea>--}}
{{--                                                            </div>--}}
{{--                                                        </div>--}}
{{--                                                    </div>--}}
{{--                                                    <div class="row">--}}
{{--                                                        <div class="col-md-12">--}}
{{--                                                            <div class="form-group pull-right">--}}
{{--                                                                <button class="btn btn-theme" type="submit">Submit Review</button>--}}
{{--                                                            </div>--}}
{{--                                                        </div>--}}
{{--                                                    </div>--}}
{{--                                                </div>--}}
{{--                                            </form>--}}
{{--                                        </div>--}}
{{--                                        <div class="review-content">--}}
{{--                                            <div class="review-item">--}}
{{--                                                <ul class="review-rating">--}}
{{--                                                    <li><i class="fa fa-star"></i></li>--}}
{{--                                                    <li><i class="fa fa-star"></i></li>--}}
{{--                                                    <li><i class="fa fa-star"></i></li>--}}
{{--                                                    <li><i class="fa fa-star"></i></li>--}}
{{--                                                    <li><i class="fa fa-star-o"></i></li>--}}
{{--                                                </ul>--}}
{{--                                                <h4 class="title">Cobus Bester</h4>--}}
{{--                                                <h5 class="review-date"><span>Cobus Bester</span> on <span>Mar 03, 2021</span></h5>--}}
{{--                                                <p>Can’t wait to start mixin’ with this one! Irba-irr-Up-up-up-up-date your theme!</p>--}}
{{--                                                <a class="review-report" href="#/">Report as Inappropriate</a>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                        <div class="review-content">--}}
{{--                                            <div class="review-item">--}}
{{--                                                <ul class="review-rating">--}}
{{--                                                    <li><i class="fa fa-star"></i></li>--}}
{{--                                                    <li><i class="fa fa-star"></i></li>--}}
{{--                                                    <li><i class="fa fa-star"></i></li>--}}
{{--                                                    <li><i class="fa fa-star"></i></li>--}}
{{--                                                    <li><i class="fa fa-star-o"></i></li>--}}
{{--                                                </ul>--}}
{{--                                                <h4 class="title">Cobus Bester</h4>--}}
{{--                                                <h5 class="review-date"><span>Cobus Bester</span> on <span>Mar 05, 2021</span></h5>--}}
{{--                                                <p>Can’t wait to start mixin’ with this one! Irba-irr-Up-up-up-up-date your theme!</p>--}}
{{--                                                <a class="review-report" href="#/">Report as Inappropriate</a>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                        <div class="review-content">--}}
{{--                                            <div class="review-item">--}}
{{--                                                <ul class="review-rating">--}}
{{--                                                    <li><i class="fa fa-star"></i></li>--}}
{{--                                                    <li><i class="fa fa-star"></i></li>--}}
{{--                                                    <li><i class="fa fa-star"></i></li>--}}
{{--                                                    <li><i class="fa fa-star"></i></li>--}}
{{--                                                    <li><i class="fa fa-star-o"></i></li>--}}
{{--                                                </ul>--}}
{{--                                                <h4 class="title">Cobus Bester</h4>--}}
{{--                                                <h5 class="review-date"><span>Cobus Bester</span> on <span>Mar 07, 2021</span></h5>--}}
{{--                                                <p>Can’t wait to start mixin’ with this one! Irba-irr-Up-up-up-up-date your theme!</p>--}}
{{--                                                <a class="review-report" href="#/">Report as Inappropriate</a>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
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
