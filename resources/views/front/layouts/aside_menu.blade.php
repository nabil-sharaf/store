<!--== Start Product Quick View ==-->
<aside class="product-quick-view-modal">
    <div class="product-quick-view-inner">
        <div class="product-quick-view-content">
            <button type="button" class="btn-close">
                <span class="pe-7s-close"><i class="lastudioicon-e-remove"></i></span>
            </button>
            <div class="row row-gutter-0">
                <div class="col-lg-6 col-md-6 col-12">
                    <div class="thumb product-images-slider">
                        <img src="assets/img/shop/quick-view1.jpg" alt="{{ __('aside_menu.image_alt') }}">
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-12">
                    <div class="single-product-info">
                        <h4 class="title product-name">{{ __('aside_menu.product_title') }}</h4>
                        <div class="prices">

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
{{--                        <div class="single-product-featured">--}}
{{--                            <ul>--}}
{{--                                <li><i class="fa fa-check"></i> {{ __('aside_menu.money_return') }}</li>--}}
{{--                            </ul>--}}
{{--                        </div>--}}
                        <p class="product-desc">{{ __('aside_menu.product_description') }}</p>
                        <div class="quick-product-action">
                            <div class="action-top">
                                <div class="pro-qty">
                                    <input type="text" id="quantity" title="{{ __('aside_menu.quantity') }}" value="01" />
                                </div>
                                <button class="btn btn-theme font-weight-bold">{{ __('aside_menu.add_to_cart') }}</button>
                                <a class="btn-wishlist"
                                   href="" onclick="wishListAdd(event,this)">{{ __('aside_menu.add_to_wishlist') }}</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="canvas-overlay"></div>
</aside>
<!--== End Product Quick View ==-->

<!--== Start Aside Search Menu ==-->
<div class="search-box-wrapper">
    <div class="search-box-content-inner">
        <div class="search-box-form-wrap">
            <div class="search-note">
                <p>{{ __('aside_menu.start_typing_press_enter') }}</p>
            </div>
            <form action="{{ route('product.search') }}" method="GET">
                <div class="search-form position-relative">
                    <label for="search-input" class="sr-only">{{ __('aside_menu.search') }}</label>
                    <input type="search" name="search" class="form-control" placeholder="{{ __('aside_menu.search') }}" id="search-input">
                    <button class="search-button"><i class="pe-7s-search"></i></button>
                </div>
            </form>
        </div>
    </div>
    <a href="javascript:;" class="search-close"><i class="pe-7s-close"></i></a>
</div>
<!--== End Aside Search Menu ==-->

<!--== Start Sidebar Cart Menu ==-->
<aside class="sidebar-cart-modal">
    <div class="sidebar-cart-inner">
        <div class="sidebar-cart-content">
            <a class="cart-close" href="javascript:void(0);"><i class="pe-7s-close"></i></a>
            <div class="sidebar-cart-all">
                <div class="cart-header">
                    <h3>{{ __('aside_menu.shopping_cart') }}</h3>
                    <div class="close-style-wrap">
                        <span class="close-style close-style-width-1 cart-close"></span>
                    </div>
                </div>
                <div class="cart-content cart-content-padding">
                    <ul id="cart-items-list">
                        <!-- العناصر سيتم إضافتها ديناميكيًا بواسطة JavaScript -->
                    </ul>
                    <div class="cart-total">
                        <h4>{{ __('aside_menu.total') }}: <span id="cart-total-price">0.00</span></h4>
                    </div>
                    <div class="cart-checkout-btn">
                        @if(session()->has('editing_order_id'))
                            <a class="cart-btn" href="{{ route('home.shop-cart',session()->get('editing_order_id')) }}">{{ __('aside_menu.view_cart') }}</a>
                            <a class="checkout-btn" href="{{ route('checkout.indexEdit',session()->get('editing_order_id')) }}">{{ __('aside_menu.checkout_edit') }}</a>
                        @else
                           <a class="cart-btn" href="{{ route('home.shop-cart') }}">{{ __('aside_menu.view_cart') }}</a>
                            <a class="checkout-btn" href="{{ route('checkout.index') }}">{{ __('aside_menu.checkout') }}</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</aside>
<div class="sidebar-cart-overlay"></div>
<!--== End Sidebar Cart Menu ==-->

<!--== Start Side Menu ==-->
<aside class="off-canvas-wrapper">
    <div class="off-canvas-inner">
        <div class="off-canvas-overlay d-none"></div>
        <!-- Start Off Canvas Content Wrapper -->
        <div class="off-canvas-content">
            <!-- Off Canvas Header -->
            <div class="off-canvas-header">
                <div class="close-action">
                    <button class="btn-close"><i class="pe-7s-close"></i></button>
                </div>
            </div>

            <div class="off-canvas-item">
                <!-- Start Mobile Menu Wrapper -->
                <div class="res-mobile-menu">
                    <!-- Note Content Auto Generate By Jquery From Main Menu -->
                </div>
                <!-- End Mobile Menu Wrapper -->
            </div>
            <!-- Off Canvas Footer -->
            <div class="off-canvas-footer"></div>
        </div>
        <!-- End Off Canvas Content Wrapper -->
    </div>
</aside>
<!--== End Side Menu ==-->
