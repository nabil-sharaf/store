@extends('front.layouts.app')
@section('title','من نحن')
@section('content')

    <!--== Start Page Title Area ==-->
    <section class="page-title-area">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-12 m-auto">
                    <div class="page-title-content text-center">
                        <h2 class="title">{{ __('about.about_us') }}</h2>
                        <div class="bread-crumbs">
                            <a href="{{ route("home.index") }}">{{ __('home.title') }}</a>
                            <span class="breadcrumb-sep"> // </span>
                            <span class="active">{{ __('about.about_us') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--== End Page Title Area ==-->

    <!--== Start Divider Area Wrapper ==-->
    <section class="divider-area divider-style3-area">
        <div class="container">
            <div class="row">
                <div class="col-md-6 col-lg-6" data-aos="fade-right" data-aos-duration="1000">
                    <div class="thumb about_us_photo">
                        <img src="{{ asset('storage/'.$siteImages?->about_us_image) }}" alt="Image">
{{--                        <div class="shape-group">--}}
{{--                            <div class="shape-style1">--}}
{{--                                <img src="{{ asset('storage/'.$siteImages?->car_icon) }}" alt="Image">--}}
{{--                            </div>--}}
{{--                        </div>--}}
                    </div>
                </div>
                <div class="col-md-6 col-lg-6" data-aos="fade-up" data-aos-duration="1000">
                    <div class="divider-content">
                        <div>
                    {!! __('about.description') !!}
                        </div>
                        <div class="text-center mt-18">
                        <a class="btn-theme text-center" href="{{ route('home.contact') }}"><strong>{{ __('contact_us.title') }}</strong></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--== End Divider Area Wrapper ==-->

    <!--== Start Brand Logo Area ==-->
    <div class="brand-logo-area brand-logo-default-area" data-aos="fade-up" data-aos-duration="1000">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-lg-12">
                    <div class="swiper-container brand-logo-slider-container">
                        <div class="swiper-wrapper brand-logo-slider">
                                @if($siteImages->sponsor_images && count($siteImages->sponsor_images)>0)
                                    @foreach($siteImages->sponsor_images as $image)
                            <div class="swiper-slide brand-logo-item">
                                         <a href="#"><img src="{{ asset('storage/'.$image) }}" alt="Brand-Logo"></a>
                             </div>
                                    @endforeach

                                @endif

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--== End Brand Logo Area ==-->

    <!--== Start Team Area ==-->
    <!--== End Team Area ==-->



@endsection
@push('styles')
<style>
    .about_us_photo{
        margin-top:-60px;
        margin-bottom: 25px!important;
    }

   .divider-area ,.divider-style3-area{
       padding-bottom: 38px!important;
   }

</style>
@endpush
