@extends('front.layouts.app')

@section('title') تسوق  منتجاتنا @endsection

@section('content')
    @if(session()->has('filteredProducts'))
        @php
            $filteredProducts = session('filteredProducts');
        @endphp
    @endif
    <!--== Start Page Title Area ==-->
    <section class="page-title-area">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-12 m-auto">
                    <div class="page-title-content text-center">
                        <h2 class="title">{{__('home.shop_now')}}</h2>
                        <div class="bread-crumbs"><a href="{{ route('home.index') }}"> الرئيسية </a><span class="breadcrumb-sep"> / </span><span class="active"> {{__('home.shop_now')}}</span></div>
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
                <div class="col-12">
                    <div class="shop-toolbar-wrap mb-4">
                        <div class="toolbar-header d-flex justify-content-between align-items-center flex-wrap">
                            <button id="toggleFilter" class="btn btn-primary mb-2 mb-md-0">
                                <i class="fa fa-filter mr-2"></i>تصفية المنتجات
                            </button>
                        </div>
                        <div id="filterContent" class="filter-content mt-3">
                            <form action="{{ route('products.filter') }}" method="get">
                                <div class="price-filter d-flex flex-column flex-md-row align-items-stretch align-items-md-center">
                                    <div class="d-flex flex-wrap align-items-center mb-2 mb-md-0 flex-grow-1">
                                        <div class="d-flex flex-grow-1">
                                            <label for="min_price" class="mb-0 ml-2 label-price">السعر:</label>
                                            <!-- Input الحد الأدنى للسعر مع ملء القيمة من الجلسة -->
                                            <input type="number" id="min_price" name="min_price" class="form-control price-input ml-2" placeholder="الحد الأدنى" value="{{ session('min_price') }}" min="0">
                                            <span class="mx-2 align-self-center">إلى</span>
                                            <!-- Input الحد الأقصى للسعر مع ملء القيمة من الجلسة -->
                                            <input type="number" id="max_price" name="max_price" class="form-control price-input mr-2" placeholder="الحد الأقصى" value="{{ session('max_price') }}" min="1">
                                        </div>
                                    </div>
                                    <div id="sorting-product" class="sorting-menu d-flex align-items-center">
                                        <span class="sorting-span mr-2">ترتيب حسب:</span>
                                        <!-- Select ترتيب المنتجات مع القيمة الافتراضية من الجلسة -->
                                        <select name="sort_by" class="form-control sorting-select">
                                            <option value="default" {{ session('sort_by') == 'default' ? 'selected' : '' }}>الافتراضي</option>
                                            <option value="price-asc" {{ session('sort_by') == 'price-asc' ? 'selected' : '' }}>السعر: من الأقل للأعلى</option>
                                            <option value="price-desc" {{ session('sort_by') == 'price-desc' ? 'selected' : '' }}>السعر: من الأعلى للأقل</option>
                                            <option value="latest" {{ session('sort_by') == 'latest' ? 'selected' : '' }}>المضاف حديثا</option>
                                        </select>
                                    </div>
                                    <!-- زر تطبيق الفلتر -->
                                    <button type="submit" class="btn filter-button btn-success w-100 mt-2 mt-md-0">تطبيق الفلتر</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="tab-content" id="nav-tabContent">
                    <div class="tab-pane fade show active" id="column-three" role="tabpanel" aria-labelledby="column-three-tab">
                        <div class="row">
                            @forelse($filteredProducts as $product)
                                    <div class="col-lg-3 col-md-4 col-sm-6 col-6"> <!-- تعديل هنا -->
                                        <!-- Start Product Item -->
                                        <div class="product-item">
                                            <div class="product-thumb">
                                                <img src="{{asset('storage/'.$product?->images?->first()?->path)}}" alt="Image">
                                                <!-- عرض البادج -->
                                                @if($product->customer_offer)
                                                    <span class="badge badge-offer">{{ $product->customer_offer }}</span>
                                                @endif
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
                                <div class="text-center"> لا يوجد عروض متاحة حاليا</div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <div class="pagination-area">
                            <nav>
                                <ul class="page-numbers">
                                    {{ $filteredProducts->links('vendor.pagination.custom') }}
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Shop Area Wrapper -->
@endsection