@extends('front.layouts.app')
@section('title','من نحن')
@section('content')

    <!--== Start Page Title Area ==-->
    <section class="page-title-area">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-12 m-auto">
                    <div class="page-title-content text-center">
                        <h2 class="title">{{ __('home.categories') }}</h2>
                        <div class="bread-crumbs">
                            <a href="{{ route("home.index") }}">{{ __('home.title') }}</a>
                            <span class="breadcrumb-sep"> // </span>
                            <span class="active">{{ __('home.categories') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--== End Page Title Area ==-->

    <!--== Start Category Area Wrapper ==-->
    <section class="category-area product-category2-area" data-aos="fade-up" data-aos-duration="1000">
        <div class="container">
            <div class="row category-items2">
                @foreach($categories as $cat)
                    <div class="col-6 col-md-6">
                        <div class="category-item">
                            <div class="thumb">
                                <img class="w-100" src="{{asset('storage/'.$cat->image)}}" alt="Image">
                                <div class="content">
                                    <div class="contact-info">
                                        <h3 class="title text-white">{{$cat->name}}</h3>
                                        <h4 class="price text-white">{!! $cat->description !!}</h4>
                                    </div>
                                    <a class="btn-theme" href="{{route('category.show',$cat->id)}}">{{__('home.shop_now')}}</a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    <!--== End Category Area Wrapper ==-->



@endsection

@push('styles')

@endpush
