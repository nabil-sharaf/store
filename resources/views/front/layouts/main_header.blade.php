<body>

<!--wrapper start-->
<div class="wrapper home-default-wrapper">

    <!--== Start Preloader Content ==-->
    <div class="preloader-wrap">
        <div class="preloader">
            <span class="dot"></span>
            <div class="dots">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </div>
    </div>
    <!--== End Preloader Content ==-->

    <!--== Start Header Wrapper ==-->
    <header class="header-wrapper">
        <div class="header-top">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12 col-sm-5 col-md-4 col-lg-6 ">
                        <div class="header-info-left text-decoration-none">
{{--                                <a href="" style="color: black;" > {{\App\Models\Admin\Setting::getValue('phone')}} <i class="fa fa-phone"></i></a>--}}
                                <a style="color: black" href="{{route('home.contact')}}"> &nbsp;&nbsp;{{\App\Models\Admin\Setting::getValue('email')}} &nbsp;<i class="fa fa-envelope"></i> </a>

                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-7 col-md-8 col-lg-6 sm-pl-0 xs-pl-15 header-top-right">
                        <div class="header-info">
                            @if(auth()->user())
                                <form id='logout-form' method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        تسجيل خروج <i class="fa fa-user"></i>
                                    </a>

                                </form>
                            @else
                            <a href="{{route('login')}}"> تسجيل <i class="fa fa-user"></i> </a>
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
                                        <img class="logo-main" style="width:120px; max-height:70px;" src="{{asset('front/assets')}}/img/logo.png" alt="Logo" />
                                        <img class="logo-light" src="{{asset('front/assets')}}/img/logo.png" alt="Logo" />
                                    </a>
                                </div>
                            </div>
                            <div class="header-align-center">
                                <div class="header-search-box">
                                    <form action="#" method="post">
                                        <div class="form-input-item">
                                            <label for="search" class="sr-only">ابحث عن شيء</label>
                                            <input type="text" id="search" placeholder="ابحث عن شيء">
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
                                            <button class="btn-wishlist" onclick="window.location.href='{{route('wishlist.index')}}'" title="قائمة الأمنيات">
                                                <i class="pe-7s-like"></i>
                                            </button>
                                        @else
                                            <button class="btn-wishlist" onclick="wishListMessage(event)" title="قائمة الأمنيات">
                                                <i class="pe-7s-like"></i>
                                            </button>
                                        @endif
                                    </div>
                                    <div class="header-action-cart">
                                        <button class="btn-cart cart-icon">
                                            <span class="cart-count">01</span>
                                            <i class="pe-7s-shopbag"></i>
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
            </div>
        </div>
        <div class="header-area header-default sticky-header">
            <div class="container">
                <div class="row row-gutter-0 align-items-center">
                    <div class="col-4 col-sm-6 col-lg-2">
                        <div class="header-logo-area">
                            <a href="{{route('home.index')}}">
                                <img class="logo-main" src="{{asset('front/assets/img/logo.png')}}" alt="Logo" />
                                <img class="logo-light" src="{{asset('front/assets/img/logo.png')}}" alt="Logo" />
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-8 sticky-md-none" style="direction: rtl">
                        <div class="header-navigation-area d-none d-md-block">
                            <ul class="main-menu nav position-relative">
                                <li><a class="ml--2" href="{{route('home.index')}}">الرئيسية</a></li>
                                <li><a href="">من نحن</a></li>

                                <li class="has-submenu"><a href="">الأقسام</a>
                                    <ul class="submenu-nav">
                                        <?php $categories = \App\Models\Admin\Category::all();?>
                                        @foreach($categories as $cat)
                                        <li><a href="{{''}}">{{$cat->name}}</a></li>

                                        @endforeach

                                    </ul>
                                </li>
                                <li><a href="{{route('home.contact')}}">اتصل بنا</a></li>
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
                                        <button class="btn-login" type="submit" title="تسجيل خروج">
                                            <i class="pe-7s-delete-user"></i>
                                        </button>

                                    </form>

                                @else

                                <button class="btn-login" title="تسجيل دخول" onclick="window.location.href='{{ route('login') }}'">
                                    <i class="pe-7s-users"></i>
                                </button>
                                @endif
                            </div>
                            <div class="header-action-wishlist">
                                @if(auth()->check())
                                <button class="btn-wishlist" onclick="window.location.href='{{route('wishlist.index')}}'" title="قائمة الأمنيات">
                                    <i class="pe-7s-like"></i>
                                </button>
                                @else
                                    <button class="btn-wishlist" onclick="wishListMessage(event)" title="قائمة الأمنيات">
                                        <i class="pe-7s-like"></i>
                                    </button>
                                @endif
                            </div>
                            <div class="header-action-cart">
                                <button class="btn-cart cart-icon">
                                    <span class="cart-count">01</span>
                                    <i class="pe-7s-shopbag"></i>
                                </button>
                            </div>
                            <button class="btn-menu d-lg-none">
                                <i class="ion-navicon"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <!--== End Header Wrapper ==-->

