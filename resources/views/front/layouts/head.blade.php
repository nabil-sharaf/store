<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>ماما ستور - @yield('title') </title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @if (LaravelLocalization::getCurrentLocale() == 'ar')

    <!--== Favicon ==-->
    <link rel="shortcut icon" href="{{asset('front/assets')}}/img/favicon.ico" type="image/x-icon" />

    <!--== Google Fonts ==-->
    <link href="https://fonts.googleapis.com/css?family=Fredoka+One:400" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Rubik:300,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

    <!--== Bootstrap CSS ==-->
    <link href="{{asset('front/assets')}}/css/bootstrap.min.css" rel="stylesheet"/>
    <!--== Font-awesome Icons CSS ==-->
    <link href="{{asset('front/assets')}}/css/font-awesome.min.css" rel="stylesheet"/>
    <!--== Pe Icon 7 Min Icons CSS ==-->
    <link href="{{asset('front/assets')}}/css/pe-icon-7-stroke.min.css" rel="stylesheet"/>
    <!--== Ionicons CSS ==-->
    <link href="{{asset('front/assets/')}}/css/ionicons.css" rel="stylesheet"/>
    <!--== Animate CSS ==-->
    <link href="{{asset('front/assets')}}/css/animate.css" rel="stylesheet"/>
    <!--== Aos CSS ==-->
    <link href="{{asset('front/assets')}}/css/aos.css" rel="stylesheet"/>
    <!--== FancyBox CSS ==-->
    <link href="{{asset('front/assets')}}/css/jquery.fancybox.min.css" rel="stylesheet"/>
    <!--== Slicknav CSS ==-->
    <link href="{{asset('front/assets')}}/css/slicknav_rtl.css" rel="stylesheet"/>
    <!--== Swiper CSS ==-->
    <link href="{{asset('front/assets')}}/css/swiper.min.css" rel="stylesheet"/>
    <!--== Slick CSS ==-->
    <link href="{{asset('front/assets')}}/css/slick_rtl.css" rel="stylesheet"/>

    {{--    font cairo--}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@200..1000&display=swap" rel="stylesheet">

    <!--== Main Style CSS ==-->
    <link href="{{asset('front/assets')}}/scss/style_rtl.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

    @else
        <!--== Favicon ==-->
        <link rel="shortcut icon" href="{{asset('front/assets')}}/img/favicon.ico" type="image/x-icon" />

        <!--== Google Fonts ==-->
        <link href="https://fonts.googleapis.com/css?family=Fredoka+One:400" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Rubik:300,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

        <!--== Bootstrap CSS ==-->
        <link href="{{asset('front/assets')}}/css/bootstrap.min.css" rel="stylesheet"/>
        <!--== Font-awesome Icons CSS ==-->
        <link href="{{asset('front/assets')}}/css/font-awesome.min.css" rel="stylesheet"/>
        <!--== Pe Icon 7 Min Icons CSS ==-->
        <link href="{{asset('front/assets')}}/css/pe-icon-7-stroke.min.css" rel="stylesheet"/>
        <!--== Ionicons CSS ==-->
        <link href="{{asset('front/assets/')}}/css/ionicons.css" rel="stylesheet"/>
        <!--== Animate CSS ==-->
        <link href="{{asset('front/assets')}}/css/animate.css" rel="stylesheet"/>
        <!--== Aos CSS ==-->
        <link href="{{asset('front/assets')}}/css/aos.css" rel="stylesheet"/>
        <!--== FancyBox CSS ==-->
        <link href="{{asset('front/assets')}}/css/jquery.fancybox.min.css" rel="stylesheet"/>
        <!--== Slicknav CSS ==-->
        <link href="{{asset('front/assets')}}/css/slicknav.css" rel="stylesheet"/>
        <!--== Swiper CSS ==-->
        <link href="{{asset('front/assets')}}/css/swiper.min.css" rel="stylesheet"/>
        <!--== Slick CSS ==-->
        <link href="{{asset('front/assets')}}/css/slick.css" rel="stylesheet"/>

        {{--    font cairo--}}
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@200..1000&display=swap" rel="stylesheet">

        <!--== Main Style CSS ==-->
        <link href="{{asset('front/assets')}}/scss/style.css" rel="stylesheet" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    @endif
    <link href="{{asset('front/assets')}}/css/my-custom-styles.css" rel="stylesheet" />

    <meta name="csrf-token" content="{{ csrf_token() }}">
    @stack('styles')
</head>
