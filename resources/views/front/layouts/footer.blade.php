<!--== Start Footer Area Wrapper ==-->
<footer class="footer-area default-style">
    <div class="footer-main">
        <div class="container">
            <div class="row">
                <div class="col-md-5 col-lg-3">
                    <div class="widget-item item-style3">
                        <div class="about-widget">
                            <a class="footer-logo" href="index.html">
                                <img style="width:100px; max-height:50px" src="{{asset('front/assets')}}/img/logo.png" alt="{{ __('footer.logo_alt') }}">
                            </a>
                            <p>{{ __('footer.description') }}</p>
                            <div class="widget-social-icons">
                                <a href="{{\App\Models\Admin\Setting::getValue('twitter')}}" target="_blank"><i class="ion-social-twitter"></i></a>
                                <a href="{{\App\Models\Admin\Setting::getValue('facebook')}}" target="_blank"><i class="ion-social-facebook"></i></a>
                                <a href="{{\App\Models\Admin\Setting::getValue('insta')}}" target="_blank"><i class="ion-social-instagram-outline"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-lg-2">
                    <div class="widget-item item-style1">
                        <h4 class="widget-title">{{ __('footer.quick_links') }}</h4>
                        <h4 class="widget-title widget-collapsed-title collapsed" data-bs-toggle="collapse" data-bs-target="#dividerId-1">{{ __('footer.quick_links') }}</h4>
                        <div id="dividerId-1" class="collapse widget-collapse-body">
                            <nav class="widget-menu-wrap">
                                <ul class="nav-menu nav item-hover-style">
                                    <li><a href="index.html">{{ __('footer.support') }}</a></li>
                                    <li><a href="index.html">{{ __('footer.helpline') }}</a></li>
                                    <li><a href="index.html">{{ __('footer.courses') }}</a></li>
                                    <li><a href="about.html">{{ __('footer.about') }}</a></li>
                                    <li><a href="index.html">{{ __('footer.event') }}</a></li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-lg-2">
                    <div class="widget-item item-style1">
                        <h4 class="widget-title">{{ __('footer.other_page') }}</h4>
                        <h4 class="widget-title widget-collapsed-title collapsed" data-bs-toggle="collapse" data-bs-target="#dividerId-2">{{ __('footer.other_page') }}</h4>
                        <div id="dividerId-2" class="collapse widget-collapse-body">
                            <nav class="widget-menu-wrap item-hover-style">
                                <ul class="nav-menu nav">
                                    <li><a href="about.html">{{ __('footer.about') }}</a></li>
                                    <li><a href="blog.html">{{ __('footer.blog') }}</a></li>
                                    <li><a href="index.html">{{ __('footer.speakers') }}</a></li>
                                    <li><a href="contact.html">{{ __('footer.contact') }}</a></li>
                                    <li><a href="index.html">{{ __('footer.tricket') }}</a></li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
                <div class="col-md-5 col-lg-2">
                    <div class="widget-item item-style2">
                        <h4 class="widget-title">{{ __('footer.company') }}</h4>
                        <h4 class="widget-title widget-collapsed-title collapsed" data-bs-toggle="collapse" data-bs-target="#dividerId-3">{{ __('footer.company') }}</h4>
                        <div id="dividerId-3" class="collapse widget-collapse-body">
                            <nav class="widget-menu-wrap item-hover-style">
                                <ul class="nav-menu nav">
                                    <li><a href="index.html">{{ __('footer.jesco') }}</a></li>
                                    <li><a href="shop.html">{{ __('footer.shop') }}</a></li>
                                    <li><a href="contact.html">{{ __('footer.contact_us') }}</a></li>
                                    <li><a href="login-register.html">{{ __('footer.log_in') }}</a></li>
                                    <li><a href="index.html">{{ __('footer.help') }}</a></li>
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
                                    <a href="index.html"><img src="{{asset('front/assets')}}/img/photos/payment1.png" alt="{{ __('footer.payment_image_alt') }}"></a>
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
