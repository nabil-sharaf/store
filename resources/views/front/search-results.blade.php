@extends('front.layouts.app')
@section('title','صفحة البحث')
@section('content')
    <!--== Start Page Title Area ==-->
    <section class="page-title-area">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-12 m-auto">
                    <div class="page-title-content text-center">
                        <h2 class="title">نتائج البحث عن  {{$query}}</h2>
                        <div class="bread-crumbs"><a href="{{ route('home.index') }}"> Home </a><span class="breadcrumb-sep"> / </span><span class="active">search</span></div>
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
                <div class="col-lg-12">
                    <div class="shop-toolbar-wrap">
                        <div class="product-showing-status">
                            <p class="count-result"><span>12 </span> Product Found of <span> 30</span></p>
                        </div>
                        <div class="product-view-mode">
                            <nav>
                                <div class="nav nav-tabs active" id="nav-tab" role="tablist">
                                    <button class="nav-link active" id="column-three-tab" data-bs-toggle="tab" data-bs-target="#column-three" type="button" role="tab" aria-controls="column-three" aria-selected="true"><i class="fa fa-th"></i></button>

                                    <button class="nav-link" id="nav-list-tab" data-bs-toggle="tab" data-bs-target="#nav-list" type="button" role="tab" aria-controls="nav-list" aria-selected="false"><i class="fa fa-list"></i></button>

                                    <button class="nav-link" id="column-two-tab" data-bs-toggle="tab" data-bs-target="#column-two" type="button" role="tab" aria-controls="column-two" aria-selected="true"><i class="fa fa-th-large"></i></button>
                                </div>
                            </nav>
                        </div>
                        <div class="product-sorting-menu product-sorting">
                            <span class="current">Sort By : <span> Default <i class="fa fa-angle-down"></i></span></span>
                            <ul>
                                <li class="active"><a href="shop.html" class="active">Sort by Default</a></li>
                                <li><a href="shop.html">Sort by Popularity</a></li>
                                <li><a href="shop.html">Sort by Rated</a></li>
                                <li><a href="shop.html">Sort by Latest</a></li>
                                <li><a href="shop.html">Sort by Price: <i class="lastudioicon-arrow-up"></i></a></li>
                                <li><a href="shop.html">Sort by Price: <i class="lastudioicon-arrow-down"></i></a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="tab-content" id="nav-tabContent">
                        <div class="tab-pane fade show active" id="column-three" role="tabpanel" aria-labelledby="column-three-tab">
                            <div class="row">
                                @forelse($products as $product)
                                    <div class="col-lg-3 col-md-4 col-sm-6">
                                        <!-- Start Product Item -->
                                        <div class="product-item">
                                            <div class="product-thumb">
                                                <img src="{{asset('storage/'.$product->images->first()->path)}}" alt="Image">
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
                                                    <span class="price">{{$product->price.' '.__('home.currency')}}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- End Product Item -->
                                    </div>
                                @empty
                                    <div class="text-center"> عفوا لا يوجد منتجات بهذا الاسم</div>
                                @endforelse
                            </div>
                        </div>

                        <div class="tab-pane fade" id="nav-list" role="tabpanel" aria-labelledby="nav-list-tab">
                            <div class="row">

                                @forelse($products as $product)
                                    <div class="col-12 product-items-list">
                                        <!-- Start Product Item -->
                                        <div class="product-item">
                                            <div class="product-thumb">
                                                <img src="{{asset('storage/'.$product->images->first()->path)}}" alt="Image">
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
                                                    <span class="price">{{$product->price.' '.__('home.currency')}}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- End Product Item -->
                                    </div>
                                @empty
                                    <div class="text-center"> عفوا لا يوجد منتجات بهذا الاسم</div>
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
                                                <img src="{{asset('storage/'.$product->images->first()->path)}}" alt="Image">
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
                                                    <span class="price">{{$product->price.' '.__('home.currency')}}</span>
                                                </div>
                                            </div>
                                        </div>                                    <!-- End Product Item -->
                                    </div>
                                @empty
                                    <div class="text-center"> عفوا لا يوجد منتجات بهذا الاسم</div>
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
