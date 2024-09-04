@extends('front.layouts.app')
@section('title',' اتصل بنا')
@section('content')

    <!--== Start Page Title Area ==-->
    <section class="page-title-area">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-12 m-auto">
                    <div class="page-title-content text-center">
                        <h2 class="title"> {{ __('contact_us.title') }}</h2>
                        <div class="bread-crumbs">
                            <a href="{{ route('home.index') }}"> {{ __('home.title') }} </a>
                            <span class="breadcrumb-sep"> / </span>
                            <span class="active"> {{ __('contact_us.contact_us') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--== End Page Title Area ==-->

    <!--== Start Contact Area ==-->
    <section class="contact-area">
        <div class="container">
            <div class="contact-page-wrap">
                <div class="row">
                    <div class="col-lg-10 m-auto">
                        <div class="contact-info-items text-center">
                            <div class="row row-gutter-80">
                                <div class="col-sm-6 col-md-4">
                                    <div class="contact-info-item">
                                        <div class="icon">
                                            <img class="icon-img" src="{{ asset('front/assets/img/icons/5.png') }}" alt="Icon">
                                        </div>
                                        <h2>{{ __('contact_us.address') }}</h2>
                                        <div class="content">
                                            <p>{{ \App\Models\Admin\Setting::getValue('address') }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-4">
                                    <div class="contact-info-item mt-xs-30">
                                        <div class="icon">
                                            <img class="icon-img" src="{{ asset('front/assets/img/icons/6.png') }}" alt="Icon">
                                        </div>
                                        <h2>{{ __('contact_us.phone') }}</h2>
                                        <div class="content">
                                            {{ \App\Models\Admin\Setting::getValue('phone') }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-4">
                                    <div class="contact-info-item mt-sm-30">
                                        <div class="icon">
                                            <img class="icon-img" src="{{ asset('front/assets/img/icons/7.png') }}" alt="Icon">
                                        </div>
                                        <h2>{{ __('contact_us.email_web') }}</h2>
                                        <div class="content">
                                            {{ \App\Models\Admin\Setting::getValue('email') }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="contact-map-area">
                            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d5601.418186303435!2d31.31504890000001!3d30.042308900000012!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x1458477dd6d40c81%3A0x89b64e0ecf7f6ae4!2sCity%20Stars%20Mall!5e0!3m2!1sen!2seg!4v1607294780661" allowfullscreen="" loading="lazy"></iframe>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-10 m-auto">
                        <div class="contact-form">
                            <form class="contact-form-wrapper" id="contact-form" action="https://whizthemes.com/mail-php/raju/arden/mail.php" method="post">
                                <div class="row">
                                    <div class="col-lg-12" style="direction: rtl">
                                        <div class="section-title">
                                            <h2 class="title">{{ __('contact_us.send_your_request') }}</h2>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="row" style="direction: rtl;">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <input class="form-control" type="text" name="con_name" placeholder="{{ __('contact_us.name_placeholder') }}">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <input class="form-control" type="email" name="con_email" placeholder="{{ __('contact_us.email_placeholder') }}">
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <input class="form-control" type="text" placeholder="{{ __('contact_us.subject_placeholder') }}">
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <textarea class="form-control" name="con_message" placeholder="{{ __('contact_us.message_placeholder') }}"></textarea>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group text-center">
                                                    <button class="btn btn-theme" type="submit">{{ __('contact_us.submit_button') }}</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <!-- Message Notification -->
                        <div class="form-message"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--== End Contact Area ==-->
@endsection
