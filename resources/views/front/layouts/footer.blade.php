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
                                <img src="{{asset('storage').'/'.$siteImages?->footer_image ?? ''}}"
                                     alt="{{ __('footer.logo_alt') }}">
                            </a>
                            @php $footer_desc=\App\Models\Admin\Setting::getValue('footer_message') ?? false @endphp
                            @if($footer_desc)
                                <p>{{$footer_desc}}</p>
                            @endif

                            @php
                                $socialLinks = \App\Models\Admin\Setting::whereIn('setting_key', ['social_link', 'social_link_2'])->get();
                                $facebook = \App\Models\Admin\Setting::getValue('facebook');
                                $instagram = \App\Models\Admin\Setting::getValue('insta');
                                $whatsapp = \App\Models\Admin\Setting::getValue('whats-app');
                            @endphp
                            <div class="widget-social-icons">
                                @if($facebook !='')
                                    <a href="{{$facebook}}" target="_blank"><i class="ion-social-facebook"></i></a>
                                @endif
                                @if($instagram !='')
                                    <a href="{{$instagram}}" target="_blank"><i
                                            class="ion-social-instagram-outline"></i></a>
                                @endif
                                @if($whatsapp !='')
                                    <a href="https://wa.me/2{{$whatsapp}}" target="_blank"><i
                                            class="ion-social-whatsapp-outline"></i></a>
                                @endif

                                @foreach($socialLinks as $social)
                                    @if($social && $social->setting_value && $social->social_type)
                                        <a href="{{ $social->setting_value }}" target="_blank">
                                            @if($social->social_type == 'tiktok')
                                                <i class="fa-brands fa-tiktok"></i>
                                            @elseif($social->social_type == 'youtube')
                                                <i class="ion-social-youtube"></i>
                                            @elseif($social->social_type == 'twitter')
                                                <i class="ion-social-twitter"></i>
                                            @elseif($social->social_type == 'telegram')
                                                <i class="fa-brands fa-telegram"></i>
                                         @endif
                                        </a>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-lg-3">
                    <div class="widget-item item-style1">
                        <h4 class="widget-title">{{ __('footer.quick_links') }}</h4>
                        <h4 class="widget-title widget-collapsed-title collapsed" data-bs-toggle="collapse"
                            data-bs-target="#dividerId-1">{{ __('footer.quick_links') }}</h4>
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
                        <h4 class="widget-title widget-collapsed-title collapsed" data-bs-toggle="collapse"
                            data-bs-target="#dividerId-2">{{ __('footer.other_page') }}</h4>
                        <div id="dividerId-2" class="collapse widget-collapse-body">
                            <nav class="widget-menu-wrap item-hover-style">
                                <ul class="nav-menu nav">

                                    @php
                                        $count = 0;
                                    $categories3 =\app\Models\Admin\Category::all();
                                    @endphp
                                    @foreach($categories3 as $category)
                                        @if($count < 3)
                                            <li>
                                                <a href="{{route('category.show',$category->id)}}">{{ $category->name }}</a>
                                            </li>
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
                        <h4 class="widget-title widget-collapsed-title collapsed" data-bs-toggle="collapse"
                            data-bs-target="#dividerId-4">{{ __('footer.store_information') }}</h4>
                        <div id="dividerId-4" class="collapse widget-collapse-body">
                            <p class="widget-address">{{\App\Models\Admin\Setting::getValue('address')}}</p>
                            <ul class="widget-contact-info">
                                <li>{{ __('footer.phone_fax') }}: <a
                                        href="#">{{\App\Models\Admin\Setting::getValue('phone')}}</a></li>
                                <li>{{ __('footer.email') }}: <a
                                        href="mailto://{{\App\Models\Admin\Setting::getValue('email')}}">{{\App\Models\Admin\Setting::getValue('email')}}</a>
                                </li>
                            </ul>
                            <div class="widget-payment-info">
                                <div class="thumb">
                                    <a href=""><img src="{{asset('storage').'/'.$siteImages?->payment_image ?? ''}}"
                                                    alt="{{ __('footer.payment_image_alt') }}"></a>
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
                            <p><i class="fa fa-copyright"></i> 2024
                                <span>{{ __('footer.site_name') }}</span> {{ __('footer.made_with') }} <i
                                    class="fa fa-heart"></i> {{ __('footer.by') }} <a target="_blank"
                                                                                      href="https://www.hasthemes.com">{{ __('footer.has_themes') }}</a>
                            </p>
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
