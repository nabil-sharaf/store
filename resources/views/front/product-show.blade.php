@extends('front.layouts.app')
@section('content','تفاصيل المنج')
    <!--== Start Page Title Area ==-->
    <section class="page-title-area">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-12 m-auto">
                    <div class="page-title-content text-center">
                        <h2 class="title">Product</h2>
                        <div class="bread-crumbs"><a href="index.html"> Home </a><span class="breadcrumb-sep"> // </span><span class="active"> Product</span></div>
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
                                    <div class="swiper-slide zoom zoom-hover">
                                        <div class="thumb-item">
                                            <a class="lightbox-image" data-fancybox="gallery" href="assets/img/shop/details/1.jpg">
                                                <img src="assets/img/shop/details/1.jpg" alt="Image-HasTech">
                                            </a>
                                        </div>
                                    </div>
                                    <div class="swiper-slide zoom zoom-hover">
                                        <div class="thumb-item">
                                            <a class="lightbox-image" data-fancybox="gallery" href="assets/img/shop/details/2.jpg">
                                                <img src="assets/img/shop/details/2.jpg" alt="Image-HasTech">
                                            </a>
                                        </div>
                                    </div>
                                    <div class="swiper-slide zoom zoom-hover">
                                        <div class="thumb-item">
                                            <a class="lightbox-image" data-fancybox="gallery" href="assets/img/shop/details/3.jpg">
                                                <img src="assets/img/shop/details/3.jpg" alt="Image-HasTech">
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="single-product-nav">
                            <div class="swiper-container single-product-nav-slider">
                                <div class="swiper-wrapper">
                                    <div class="swiper-slide">
                                        <div class="nav-item">
                                            <img src="assets/img/shop/details/nav1.jpg" alt="Image-HasTech">
                                        </div>
                                    </div>
                                    <div class="swiper-slide">
                                        <div class="nav-item">
                                            <img src="assets/img/shop/details/nav2.jpg" alt="Image-HasTech">
                                        </div>
                                    </div>
                                    <div class="swiper-slide">
                                        <div class="nav-item">
                                            <img src="assets/img/shop/details/nav3.jpg" alt="Image-HasTech">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="single-product-info">
                        <h4 class="title">Jigsaw Puzzles For Kids</h4>
                        <div class="prices">
                            <span class="price">$120.59</span>
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
                                <a href="#/">( 5 Customer Review )</a>
                            </div>
                        </div>
                        <div class="single-product-featured">
                            <ul>
                                <li><i class="fa fa-check"></i> Free Shipping</li>
                                <li><i class="fa fa-check"></i> Support 24/7</li>
                                <li><i class="fa fa-check"></i> Money Return</li>
                            </ul>
                        </div>
                        <p class="product-desc">Lorem ipsum dolor sit amet, consect adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quisll exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duisol aute irure dolor in reprehenderit.</p>
                        <div class="quick-product-action">
                            <div class="action-top">
                                <div class="pro-qty">
                                    <input type="text" id="quantity" title="Quantity" value="01" />
                                </div>
                                <button class="btn btn-theme">Add to Cart</button>
                                <a class="btn-wishlist" href="shop-wishlist.html">Add to Wishlist</a>
                            </div>
                        </div>
                        <div class="widget">
                            <h3 class="title">SKU:</h3>
                            <div class="widget-tags">
                                <span>Ch-256xl</span>
                            </div>
                        </div>
                        <div class="widget">
                            <h3 class="title">Categories:</h3>
                            <div class="widget-tags">
                                <a href="blog.html">Toys.</a>
                                <a href="blog.html">Dresss</a>
                            </div>
                        </div>
                        <div class="widget">
                            <h3 class="title">Tag:</h3>
                            <div class="widget-tags">
                                <a href="blog.html">Toys,</a>
                                <a href="blog.html">Dress</a>
                            </div>
                        </div>
                        <div class="widget">
                            <h3 class="title">Share:</h3>
                            <div class="widget-tags widget-share">
                                <span class="fa fa-facebook"></span>
                                <span class="fa fa-dribbble"></span>
                                <span class="fa fa-pinterest-p"></span>
                                <span class="fa fa-twitter"></span>
                                <span class="fa fa-linkedin"></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="product-description-review">
                            <ul class="nav nav-tabs product-description-tab-menu" id="myTab" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="product-aditional-tab" data-bs-toggle="tab" data-bs-target="#commentProduct" type="button" role="tab" aria-selected="false">Information</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="product-desc-tab" data-bs-toggle="tab" data-bs-target="#productDesc" type="button" role="tab" aria-controls="productDesc" aria-selected="true">Description</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="product-review-tab" data-bs-toggle="tab" data-bs-target="#productReview" type="button" role="tab" aria-controls="productReview" aria-selected="false">Reviews (03)</button>
                                </li>
                            </ul>
                            <div class="tab-content product-description-tab-content" id="myTabContent">
                                <div class="tab-pane fade" id="commentProduct" role="tabpanel" aria-labelledby="product-aditional-tab">
                                    <div class="product-desc">
                                        <p>Lorem ipsum dolor sit amet, consectetur adipisici elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum. Sed utlo perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt.</p>
                                    </div>
                                </div>
                                <div class="tab-pane fade show active" id="productDesc" role="tabpanel" aria-labelledby="product-desc-tab">
                                    <div class="product-desc">
                                        <p>Lorem ipsum dolor sit amet, consectetur adipisici elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum. Sed utlo perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt.</p>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="productReview" role="tabpanel" aria-labelledby="product-review-tab">
                                    <div class="product-review">
                                        <div class="review-header">
                                            <h4 class="title">Customer Reviews</h4>
                                            <div class="review-info">
                                                <ul class="review-rating">
                                                    <li><i class="fa fa-star"></i></li>
                                                    <li><i class="fa fa-star"></i></li>
                                                    <li><i class="fa fa-star"></i></li>
                                                    <li><i class="fa fa-star"></i></li>
                                                    <li><i class="fa fa-star-o"></i></li>
                                                </ul>
                                                <span class="review-caption">Based on 1 review</span>
                                                <span class="review-write-btn">Write a review</span>
                                            </div>
                                        </div>
                                        <div class="product-review-form">
                                            <h4 class="title">Write a review</h4>
                                            <form action="#" method="post">
                                                <div class="review-form-content">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label for="reviewFormName">Name</label>
                                                                <input class="form-control" id="reviewFormName" type="text" placeholder="Enter your name" required="">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label for="reviewFormEmail">Email</label>
                                                                <input class="form-control" id="reviewFormEmail" type="email" placeholder="john.smith@example.com" required="">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <div class="rating">
                                                                <span class="rating-title">Rating</span>
                                                                <span>
                                    <a class="fa fa-star-o" href="#/"></a>
                                    <a class="fa fa-star-o" href="#/"></a>
                                    <a class="fa fa-star-o" href="#/"></a>
                                    <a class="fa fa-star-o" href="#/"></a>
                                    <a class="fa fa-star-o" href="#/"></a>
                                  </span>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label for="reviewReviewTitle">Review Title</label>
                                                                <input class="form-control" id="reviewReviewTitle" type="text" placeholder="Give your review a title" required="">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label for="reviewFormTextarea">Body of Review <span>(1500)</span></label>
                                                                <textarea class="form-control textarea" id="reviewFormTextarea" name="comment" rows="7" placeholder="Write your comments here" required=""></textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="form-group pull-right">
                                                                <button class="btn btn-theme" type="submit">Submit Review</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                        <div class="review-content">
                                            <div class="review-item">
                                                <ul class="review-rating">
                                                    <li><i class="fa fa-star"></i></li>
                                                    <li><i class="fa fa-star"></i></li>
                                                    <li><i class="fa fa-star"></i></li>
                                                    <li><i class="fa fa-star"></i></li>
                                                    <li><i class="fa fa-star-o"></i></li>
                                                </ul>
                                                <h4 class="title">Cobus Bester</h4>
                                                <h5 class="review-date"><span>Cobus Bester</span> on <span>Mar 03, 2021</span></h5>
                                                <p>Can’t wait to start mixin’ with this one! Irba-irr-Up-up-up-up-date your theme!</p>
                                                <a class="review-report" href="#/">Report as Inappropriate</a>
                                            </div>
                                        </div>
                                        <div class="review-content">
                                            <div class="review-item">
                                                <ul class="review-rating">
                                                    <li><i class="fa fa-star"></i></li>
                                                    <li><i class="fa fa-star"></i></li>
                                                    <li><i class="fa fa-star"></i></li>
                                                    <li><i class="fa fa-star"></i></li>
                                                    <li><i class="fa fa-star-o"></i></li>
                                                </ul>
                                                <h4 class="title">Cobus Bester</h4>
                                                <h5 class="review-date"><span>Cobus Bester</span> on <span>Mar 05, 2021</span></h5>
                                                <p>Can’t wait to start mixin’ with this one! Irba-irr-Up-up-up-up-date your theme!</p>
                                                <a class="review-report" href="#/">Report as Inappropriate</a>
                                            </div>
                                        </div>
                                        <div class="review-content">
                                            <div class="review-item">
                                                <ul class="review-rating">
                                                    <li><i class="fa fa-star"></i></li>
                                                    <li><i class="fa fa-star"></i></li>
                                                    <li><i class="fa fa-star"></i></li>
                                                    <li><i class="fa fa-star"></i></li>
                                                    <li><i class="fa fa-star-o"></i></li>
                                                </ul>
                                                <h4 class="title">Cobus Bester</h4>
                                                <h5 class="review-date"><span>Cobus Bester</span> on <span>Mar 07, 2021</span></h5>
                                                <p>Can’t wait to start mixin’ with this one! Irba-irr-Up-up-up-up-date your theme!</p>
                                                <a class="review-report" href="#/">Report as Inappropriate</a>
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
    <!--== End Shop Area ==-->

    <!--== Start Shop Area ==-->
    <section class="product-slider-area related-product-area">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 m-auto">
                    <div class="section-title text-center">
                        <h2 class="title">Related Product</h2>
                        <div class="desc">
                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod incididunt ut labore et dolore magna aliqua.</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="product-tab1-slider">
                        <div class="slide-item">
                            <!-- Start Product Item -->
                            <div class="product-item">
                                <div class="product-thumb">
                                    <img src="assets/img/shop/9.png" alt="Image">
                                    <div class="product-action">
                                        <a class="action-quick-view" href="shop-cart.html"><i class="ion-ios-cart"></i></a>
                                        <a class="action-quick-view" href="javascript:void(0)"><i class="ion-arrow-expand"></i></a>
                                        <a class="action-quick-view" href="shop-wishlist.html"><i class="ion-heart"></i></a>
                                        <a class="action-quick-view" href="shop-compare.html"><i class="ion-shuffle"></i></a>
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
                                    <h4 class="title"><a href="shop-single-product.html">Funskool Teddy Brown</a></h4>
                                    <div class="prices">
                                        <span class="price">$190.12</span>
                                    </div>
                                </div>
                            </div>
                            <!-- End Product Item -->
                        </div>
                        <div class="slide-item">
                            <!-- Start Product Item -->
                            <div class="product-item">
                                <div class="product-thumb">
                                    <img src="assets/img/shop/10.png" alt="Image">
                                    <div class="product-action">
                                        <a class="action-quick-view" href="shop-cart.html"><i class="ion-ios-cart"></i></a>
                                        <a class="action-quick-view" href="javascript:void(0)"><i class="ion-arrow-expand"></i></a>
                                        <a class="action-quick-view" href="shop-wishlist.html"><i class="ion-heart"></i></a>
                                        <a class="action-quick-view" href="shop-compare.html"><i class="ion-shuffle"></i></a>
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
                                    <h4 class="title"><a href="shop-single-product.html">Newborn Kit Set</a></h4>
                                    <div class="prices">
                                        <span class="price">$190.12</span>
                                    </div>
                                </div>
                            </div>
                            <!-- End Product Item -->
                        </div>
                        <div class="slide-item">
                            <!-- Start Product Item -->
                            <div class="product-item">
                                <div class="product-thumb">
                                    <img src="assets/img/shop/11.png" alt="Image">
                                    <div class="product-action">
                                        <a class="action-quick-view" href="shop-cart.html"><i class="ion-ios-cart"></i></a>
                                        <a class="action-quick-view" href="javascript:void(0)"><i class="ion-arrow-expand"></i></a>
                                        <a class="action-quick-view" href="shop-wishlist.html"><i class="ion-heart"></i></a>
                                        <a class="action-quick-view" href="shop-compare.html"><i class="ion-shuffle"></i></a>
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
                                    <h4 class="title"><a href="shop-single-product.html">Classic Fisher Gift</a></h4>
                                    <div class="prices">
                                        <span class="price">$190.12</span>
                                    </div>
                                </div>
                            </div>
                            <!-- End Product Item -->
                        </div>
                        <div class="slide-item">
                            <!-- Start Product Item -->
                            <div class="product-item">
                                <div class="product-thumb">
                                    <img src="assets/img/shop/12.png" alt="Image">
                                    <div class="product-action">
                                        <a class="action-quick-view" href="shop-cart.html"><i class="ion-ios-cart"></i></a>
                                        <a class="action-quick-view" href="javascript:void(0)"><i class="ion-arrow-expand"></i></a>
                                        <a class="action-quick-view" href="shop-wishlist.html"><i class="ion-heart"></i></a>
                                        <a class="action-quick-view" href="shop-compare.html"><i class="ion-shuffle"></i></a>
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
                                    <h4 class="title"><a href="shop-single-product.html">Sassy Crib and Floor Mirror</a></h4>
                                    <div class="prices">
                                        <span class="price">$190.12</span>
                                    </div>
                                </div>
                            </div>
                            <!-- End Product Item -->
                        </div>
                        <div class="slide-item">
                            <!-- Start Product Item -->
                            <div class="product-item">
                                <div class="product-thumb">
                                    <img src="assets/img/shop/9.png" alt="Image">
                                    <div class="product-action">
                                        <a class="action-quick-view" href="shop-cart.html"><i class="ion-ios-cart"></i></a>
                                        <a class="action-quick-view" href="javascript:void(0)"><i class="ion-arrow-expand"></i></a>
                                        <a class="action-quick-view" href="shop-wishlist.html"><i class="ion-heart"></i></a>
                                        <a class="action-quick-view" href="shop-compare.html"><i class="ion-shuffle"></i></a>
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
                                    <h4 class="title"><a href="shop-single-product.html">Funskool Teddy Brown</a></h4>
                                    <div class="prices">
                                        <span class="price">$190.12</span>
                                    </div>
                                </div>
                            </div>
                            <!-- End Product Item -->
                        </div>
                        <div class="slide-item">
                            <!-- Start Product Item -->
                            <div class="product-item">
                                <div class="product-thumb">
                                    <img src="assets/img/shop/10.png" alt="Image">
                                    <div class="product-action">
                                        <a class="action-quick-view" href="shop-cart.html"><i class="ion-ios-cart"></i></a>
                                        <a class="action-quick-view" href="javascript:void(0)"><i class="ion-arrow-expand"></i></a>
                                        <a class="action-quick-view" href="shop-wishlist.html"><i class="ion-heart"></i></a>
                                        <a class="action-quick-view" href="shop-compare.html"><i class="ion-shuffle"></i></a>
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
                                    <h4 class="title"><a href="shop-single-product.html">Newborn Kit Set</a></h4>
                                    <div class="prices">
                                        <span class="price">$190.12</span>
                                    </div>
                                </div>
                            </div>
                            <!-- End Product Item -->
                        </div>
                        <div class="slide-item">
                            <!-- Start Product Item -->
                            <div class="product-item">
                                <div class="product-thumb">
                                    <img src="assets/img/shop/11.png" alt="Image">
                                    <div class="product-action">
                                        <a class="action-quick-view" href="shop-cart.html"><i class="ion-ios-cart"></i></a>
                                        <a class="action-quick-view" href="javascript:void(0)"><i class="ion-arrow-expand"></i></a>
                                        <a class="action-quick-view" href="shop-wishlist.html"><i class="ion-heart"></i></a>
                                        <a class="action-quick-view" href="shop-compare.html"><i class="ion-shuffle"></i></a>
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
                                    <h4 class="title"><a href="shop-single-product.html">Classic Fisher Gift</a></h4>
                                    <div class="prices">
                                        <span class="price">$190.12</span>
                                    </div>
                                </div>
                            </div>
                            <!-- End Product Item -->
                        </div>
                        <div class="slide-item">
                            <!-- Start Product Item -->
                            <div class="product-item">
                                <div class="product-thumb">
                                    <img src="assets/img/shop/12.png" alt="Image">
                                    <div class="product-action">
                                        <a class="action-quick-view" href="shop-cart.html"><i class="ion-ios-cart"></i></a>
                                        <a class="action-quick-view" href="javascript:void(0)"><i class="ion-arrow-expand"></i></a>
                                        <a class="action-quick-view" href="shop-wishlist.html"><i class="ion-heart"></i></a>
                                        <a class="action-quick-view" href="shop-compare.html"><i class="ion-shuffle"></i></a>
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
                                    <h4 class="title"><a href="shop-single-product.html">Sassy Crib and Floor Mirror</a></h4>
                                    <div class="prices">
                                        <span class="price">$190.12</span>
                                    </div>
                                </div>
                            </div>
                            <!-- End Product Item -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--== End Shop Area ==-->
@endsection
