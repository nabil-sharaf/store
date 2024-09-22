@php
$siteImages = \App\Models\Admin\SiteImage::first() ?? null;
 @endphp
<!--== Start Footer Area Wrapper ==-->
<footer class="footer-area default-style">
    <div class="footer-main">
        <div class="container">
            <div class="row">
                <div class="col-md-5 col-lg-3">
                    <div class="widget-item item-style3">
                        <div class="about-widget">
                            <a class="footer-logo" href="">
                                <img style="width:100px; max-height:50px" src="{{asset('storage').'/'.$siteImages->footer_image ?? ''}}" alt="{{ __('footer.logo_alt') }}">
                            </a>
                            <p>{{ __('footer.description') }}</p>
                            <div class="widget-social-icons">
                                <a href="{{\App\Models\Admin\Setting::getValue('facebook')}}" target="_blank"><i class="ion-social-facebook"></i></a>
                                <a href="{{\App\Models\Admin\Setting::getValue('insta')}}" target="_blank"><i class="ion-social-instagram-outline"></i></a>
                                <a href="{{\App\Models\Admin\Setting::getValue('whats-app')}}" target="_blank"><i class="ion-social-whatsapp-outline"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-lg-3">
                    <div class="widget-item item-style1">
                        <h4 class="widget-title">{{ __('footer.quick_links') }}</h4>
                        <h4 class="widget-title widget-collapsed-title collapsed" data-bs-toggle="collapse" data-bs-target="#dividerId-1">{{ __('footer.quick_links') }}</h4>
                        <div id="dividerId-1" class="collapse widget-collapse-body">
                            <nav class="widget-menu-wrap">
                                <ul class="nav-menu nav item-hover-style">
                                    <li><a href="{{route('home.index')}}">{{ __('footer.home') }}</a></li>
                                    <li><a href="{{route('home.contact')}}">{{ __('footer.contact') }}</a></li>
                                    <li><a href="{{route('home.about')}}">{{ __('footer.about') }}</a></li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-lg-3">
                    <div class="widget-item item-style1">
                        <h4 class="widget-title">{{ __('footer.categories') }}</h4>
                        <h4 class="widget-title widget-collapsed-title collapsed" data-bs-toggle="collapse" data-bs-target="#dividerId-2">{{ __('footer.other_page') }}</h4>
                        <div id="dividerId-2" class="collapse widget-collapse-body">
                            <nav class="widget-menu-wrap item-hover-style">
                                <ul class="nav-menu nav">

                                    @php
                                        $count = 0;
                                    $categories3 =\app\Models\Admin\Category::all();
                                    @endphp
                                    @foreach($categories3 as $category)
                                        @if($count < 3)
                                            <li><a href="{{route('category.show',$category->id)}}">{{ $category->name }}</a></li>
                                            @php $count++; @endphp
                                        @else
                                            @break
                                        @endif
                                    @endforeach


                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
                <div class="col-md-7 col-lg-3">
                    <div class="widget-item">
                        <h4 class="widget-title">{{ __('footer.store_information') }}</h4>
                        <h4 class="widget-title widget-collapsed-title collapsed" data-bs-toggle="collapse" data-bs-target="#dividerId-4">{{ __('footer.store_information') }}</h4>
                        <div id="dividerId-4" class="collapse widget-collapse-body">
                            <p class="widget-address">{{\App\Models\Admin\Setting::getValue('address')}}</p>
                            <ul class="widget-contact-info">
                                <li>{{ __('footer.phone_fax') }}: <a href="#">{{\App\Models\Admin\Setting::getValue('phone')}}</a></li>
                                <li>{{ __('footer.email') }}: <a href="mailto://{{\App\Models\Admin\Setting::getValue('email')}}">{{\App\Models\Admin\Setting::getValue('email')}}</a></li>
                            </ul>
                            <div class="widget-payment-info">
                                <div class="thumb">
                                    <a href=""><img src="{{asset('storage').'/'.$siteImages->payment_image ?? ''}}" alt="{{ __('footer.payment_image_alt') }}"></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="footer-bottom">
        <div class="container">
            <div class="footer-bottom-content">
                <div class="row text-center">
                    <div class="col-sm-12">
                        <div class="widget-copyright">
                            <p><i class="fa fa-copyright"></i> 2024 <span>{{ __('footer.site_name') }}</span> {{ __('footer.made_with') }} <i class="fa fa-heart"></i> {{ __('footer.by') }} <a target="_blank" href="https://www.hasthemes.com">{{ __('footer.has_themes') }}</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="footer-shape bg-img" data-bg-img="{{asset('front/assets')}}/img/photos/footer1.png"></div>
</footer>
<!--== End Footer Area Wrapper ==-->

<!--== Scroll Top Button ==-->
<div class="scroll-to-top"><span class="fa fa-angle-double-up"></span></div>
