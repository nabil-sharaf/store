@extends('front.layouts.app')

@section('title'){{$title}} @endsection

@section('content')
    <!--== Start Page Title Area ==-->
    <section class="page-title-area">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-12 m-auto">
                    <div class="page-title-content text-center">
                        <h2 class="title">{{$title}}</h2>
                        <div class="bread-crumbs"><a href="{{ route('home.index') }}"> الرئيسية </a><span class="breadcrumb-sep"> / </span><span class="active"> {{$title}}</span></div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--== End Page Title Area ==-->

    <!--== Start Shop Area Wrapper ==-->
    <div class="product-area product-grid-area">
        <div class="container">
            <div class="row">

                    <div class="tab-content" id="nav-tabContent">
                        <div class="tab-pane fade show active" id="column-three" role="tabpanel" aria-labelledby="column-three-tab">
                            <div class="row">
                                @forelse($products as $product)
                                <div class="col-lg-3 col-md-4 col-sm-6">
                                    <!-- Start Product Item -->
                                    <div class="product-item">
                                        <div class="product-thumb">
                                            <img src="{{asset('storage/'.$product->images?->first()?->path)}}" alt="Image">
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
                                                <x-product-price :productPrice="$product->product_price" :discountedPrice="$product->discounted_price" />
                                            </div>
                                        </div>
                                    </div>
                                    <!-- End Product Item -->
                                </div>
                                @empty
                                    <div> هذا القسم لا يحتوي على منتجات حاليا</div>
                                @endforelse
                            </div>
                        </div>

                        <div class="tab-pane fade" id="nav-list" role="tabpanel" aria-labelledby="nav-list-tab">
                            <div class="row">

                                @forelse($products as $product)
                                    <div class="col-lg-3 col-md-4 col-sm-6">
                                        <!-- Start Product Item -->
                                        <div class="product-item">
                                            <div class="product-thumb">
                                                <img src="{{ asset('storage/' . $product->images?->first()?->path) }}" alt="{{ $product->name }}">

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
                                                    <x-product-price :productPrice="$product->product_price" :discountedPrice="$product->discounted_price" />
                                                </div>
                                            </div>
                                        </div>
                                        <!-- End Product Item -->
                                    </div>
                                @empty
                                    <div> هذا القسم لا يحتوي على منتجات حاليا</div>
                                @endforelse
                            </div>
                        </div>
                        <div class="tab-pane fade" id="column-two" role="tabpanel" aria-labelledby="column-two-tab">
                            <div class="row">
                                @forelse($products as $product)
                                <div class="col-sm-6">
                                    <!-- Start Product Item -->
                                    <div class="product-item">
                                        <div class="product-thumb">
                                            <img src="{{asset('storage/'.$product->images?->first()?->path)}}" alt="Image">
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
                                                <x-product-price :productPrice="$product->product_price" :discountedPrice="$product->discounted_price" />
                                            </div>
                                        </div>
                                    </div>                                    <!-- End Product Item -->
                                </div>
                                @empty
                                    <div> هذا القسم لا يحتوي على منتجات حاليا</div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="pagination-area">
                                <nav>
                                    <ul class="page-numbers">
                                        <li>
                                            <a class="page-number active" href="shop.html">1</a>
                                        </li>
                                        <li>
                                            <a class="page-number" href="shop.html">2</a>
                                        </li>
                                        <li>
                                            <a class="page-number" href="shop.html">3</a>
                                        </li>
                                        <li>
                                            <a class="page-number next" href="shop.html">
                                                <i class="fa fa-angle-right"></i>
                                            </a>
                                        </li>
                                    </ul>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--== End Shop Area Wrapper ==-->
@endsection
