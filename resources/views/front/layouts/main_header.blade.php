<body>

<!--wrapper start-->
<div class="wrapper home-default-wrapper">

    <!--== Start Header Wrapper ==-->

    <header class="header-wrapper">
        <div class="header-top">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12 col-sm-5 col-md-4 col-lg-6">
                        <div class="header-info-left d-flex align-items-center justify-content-center justify-content-sm-start">
                            <p class="mb-0 mr-3">{!! __('main_header.free_returns_shipping')  !!} </p>

                            <!-- Dropdown for language selection -->
                            <div class="dropdown lang-dropdown-link">
                                <a href="#" class="dropdown-toggle" id="languageDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fa fa-globe"></i>
                                    {{ LaravelLocalization::getCurrentLocaleName() }}
                                </a>
                                <ul class="dropdown-menu" aria-labelledby="languageDropdown">
                                    @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                                        <li>
                                            <a class="dropdown-item" href="{{ LaravelLocalization::getLocalizedURL($localeCode) }}">
                                                {{ $properties['native'] }}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-7 col-md-8 col-lg-6 sm-pl-0 xs-pl-15 header-top-right">
                        <div class="header-info">
                            <a href="#"><i class="fa fa-phone"></i> {!!  \App\Models\Admin\Setting::getValue('phone')!!}</a>
                            <a href="{{route('home.contact')}}"> <i class="fa fa-envelope"></i>{!!  \App\Models\Admin\Setting::getValue('email') !!}</a>

                            @if(auth()->check())
                                <!-- Dropdown for account information -->
                                <a href="#" class="dropdown-toggle account-link" id="accountDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fa fa-user"></i> {!!   __('main_header.account')!!}
                                </a>
                                <!-- Dropdown items -->
                                <ul class="dropdown-menu text-center" aria-labelledby="accountDropdown">
                                    <li>
                                        <a class="dropdown-item my-header-dropdown-item" href="{{route('profile.index')}}">{!!  __('main_header.account_settings') !!}</a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item my-header-dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                            {{ __('main_header.logout') }}
                                        </a>
                                    </li>
                                </ul>
                            @else
                                <!-- Single link for login -->
                                <a href="{{ route('login') }}">
                                    <i class="fa fa-user"></i> {{ __('main_header.login') }}
                                </a>
                            @endif

                            @if(auth()->check())
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    @csrf
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="header-middle">
            <div class="container">
                <div class="row row-gutter-0 align-items-center">
                    <div class="col-12">
                        <div class="header-align">
                            <div class="header-align-left">
                                <div class="header-logo-area">
                                    <a href="{{route('home.index')}}">
                                        <img class="logo-main" src="{{asset('front/assets')}}/img/logo.png"  alt="Logo" />
                                        <img class="logo-light" src="{{asset('front/assets')}}/img/logo.png"  alt="Logo" />
                                    </a>
                                </div>
                            </div>
                            <div class="header-align-center">
                                <div class="header-search-box">
                                    <form action="{{ route('product.search') }}" method="GET">
                                        <div class="form-input-item">
                                            <label for="search" class="sr-only">{{ __('main_header.search_placeholder') }}</label>
                                            <input type="text" id="search" name="search" placeholder="{{ __('main_header.search_placeholder') }}" >
                                            <button type="submit" class="btn-src">
                                                <i class="pe-7s-search"></i>
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="header-align-right">
                                <div class="header-action-area">
                                    <div class="header-action-wishlist">
                                        @if(auth()->check())
                                            <button class="btn-wishlist" onclick="window.location.href= '{{route('wishlist.index')}}' " title="{{ __('main_header.wishlist') }}">
                                                <i class="pe-7s-like"></i>
                                            </button>
                                        @else
                                            <button class="btn-wishlist" onclick="wishListMessage(event)" title="{{ __('main_header.wishlist') }}">
                                                <i class="pe-7s-like"></i>
                                            </button>
                                        @endif
                                    </div>
                                    <div class="header-action-cart">
                                        <button class="btn-cart cart-icon">
                                            <i class="pe-7s-cart"></i>
                                        </button>
                                    </div>
                                    <button class="btn-menu d-md-none">
                                        <i class="ion-navicon"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row d-md-none mt-3">
                    <div class="col-12">
                        <div class="header-search-box">
                            <form action="{{ route('product.search') }}" method="GET">
                                <div class="form-input-item">
                                    <label for="search-mobile" class="sr-only">{{ __('main_header.search_placeholder') }}</label>
                                    <input type="text" id="search-mobile" name="search" placeholder="{{ __('main_header.search_placeholder') }}">
                                    <button type="submit" class="btn-src">
                                        <i class="pe-7s-search"></i>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>        <div class="header-area header-default sticky-header">
            <div class="container">
                <div class="row row-gutter-0 align-items-center">
                    <div class="col-4 col-sm-6 col-lg-2">
                        <div class="header-logo-area">
                            <a href="{{route('home.index')}}">
                                <img class="logo-main" src="{{asset('front/assets')}}/img/logo.png"  alt="Logo" />
                                <img class="logo-light" src="{{asset('front/assets')}}/img/logo.png"  alt="Logo" />
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-8 sticky-md-none">
                        <div class="header-navigation-area d-none d-md-block">
                            <ul class="main-menu nav position-relative">
                                <li><a class="ml--2" href="{{route('home.index')}}">{{ __('main_header.home') }}</a></li>
                                <li><a href="{{route('home.about')}}">{!! __('main_header.about') !!}  </a></li>
                                <li class="has-submenu"><a href="">{!! __('main_header.categories') !!}  </a>
                                    <ul class="submenu-nav">
                                        <?php $categories = \App\Models\Admin\Category::all();?>
                                        @foreach($categories as $cat)
                                            <li><a href="{{route('category.show',$cat->id)}}">{{$cat->name}}</a></li>
                                        @endforeach
                                    </ul>
                                </li>
                                <li><a href="{{route('home.contact')}}">{!!  __('main_header.contact')!!}  </a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-8 col-sm-6 col-lg-2">
                        <div class="header-action-area">
                            <div class="header-action-search">
                                <button class="btn-search btn-search-menu">
                                    <i class="pe-7s-search"></i>
                                </button>
                            </div>
                            <div class="header-action-login">
                                @if(auth()->user())
                                    <form id='logout-form' method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button class="btn-login" type="submit" title="{{ __('main_header.logout_btn') }}">
                                            <i class="pe-7s-delete-user"></i>
                                        </button>
                                    </form>
                                @else
                                    <button class="btn-login" title="{{ __('main_header.login_btn') }}" onclick="window.location.href='{{ route('login') }}'">
                                        <i class="pe-7s-users"></i>
                                    </button>
                                @endif
                            </div>
                            <div class="header-action-wishlist">
                                @if(auth()->check())
                                    <button class="btn-wishlist" onclick="window.location.href='{{route('wishlist.index')}}'" title="{{ __('main_header.wishlist') }}">
                                        <i class="pe-7s-like"></i>
                                    </button>
                                @else
                                    <button class="btn-wishlist" onclick="wishListMessage(event)" title="{{ __('main_header.wishlist') }}">
                                        <i class="pe-7s-like"></i>
                                    </button>
                                @endif
                            </div>
                            <div class="header-action-cart">
                                <button class="btn-cart cart-icon">
{{--                                    <span class="cart-count">01</span>--}}
                                    <i class="pe-7s-cart"></i>
                                </button>
                            </div>
                            <button class="btn-menu d-md-none">
                                <i class="ion-navicon"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <!--== End Header Wrapper ==-->
