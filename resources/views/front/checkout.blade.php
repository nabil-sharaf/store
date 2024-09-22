@extends('front.layouts.app')
@section('title','اتمام الطلب')

@section('content')

    <!--== Start Page Title Area ==-->
    <section class="page-title-area">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-12 m-auto">
                    <div class="page-title-content text-center">
                        <h2 class="title">{{ __('checkout.page_title') }}</h2>
                        <div class="bread-crumbs"><a href="{{ route('home.index') }}"> {{ __('checkout.home') }} </a><span
                                class="breadcrumb-sep"> {{ __('checkout.breadcrumb_sep') }} </span><span class="active"> {{ __('checkout.checkout_page') }}</span></div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--== End Page Title Area ==-->


    <!--== Start Checkout Area Wrapper ==-->
    <section class="product-area shop-checkout-area" dir="rtl">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 m-auto">
                    <div class="section-title text-center">
                        <h2 class="title">{{ __('checkout.page_title') }}</h2>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="checkout-coupon-wrap mb-65 mb-md-40">
                        <p class="cart-page-title"><i class="ion-ios-pricetag-outline"></i> {{ __('checkout.have_coupon') }} <a class="checkout-coupon-active" href="#/">{{ __('checkout.apply_coupon') }}</a></p>
                        <div class="checkout-coupon-content">
                            <form action="#">
                                <p>{{ __('checkout.have_coupon') }}</p>
                                <input type="text" placeholder="{{ __('checkout.apply_coupon') }}">
                                <button type="submit">{{ __('checkout.apply_coupon') }}</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-7">
                    <div class="billing-info-wrap">
                        <h3>{{ __('checkout.billing_details') }}</h3>
                        <div class="row">
                            <div class="col-12">
                                <div class="billing-info mb-20">
                                    <label>{{ __('checkout.full_name') }} <abbr class="required" title="{{ __('checkout.required') }}">*</abbr></label>
                                    <input type="text" placeholder="{{__('checkout.full_name_placeholder')}}">
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="billing-info mb-20">
                                    <label>{{ __('checkout.address') }} <abbr class="required" title="{{ __('checkout.required') }}">*</abbr></label>
                                    <input class="billing-address" placeholder="{{ __('checkout.address-placeholder') }}" type="text">
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="billing-info mb-20">
                                    <label>{{ __('checkout.city') }} <abbr class="required" title="{{ __('checkout.required') }}">*</abbr></label>
                                    <input type="text" placeholder="{{ __('checkout.city_placeholder') }}">
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="billing-select mb-20">
                                    <label>{{ __('checkout.state') }} <abbr class="required" title="{{ __('checkout.required') }}">*</abbr></label>
                                    <div class="select-style ">
                                        <select class=" select-active">
                                            <option value="" disabled selected>اختر اسم محافظتك</option>
                                            <option value="القاهرة">القاهرة</option>
                                            <option value="الجيزة">الجيزة</option>
                                            <option value="الإسكندرية">الإسكندرية</option>
                                            <option value="الدقهلية">الدقهلية</option>
                                            <option value="البحر الأحمر">البحر الأحمر</option>
                                            <option value="البحيرة">البحيرة</option>
                                            <option value="الفيوم">الفيوم</option>
                                            <option value="الغربية">الغربية</option>
                                            <option value="الإسماعيلية">الإسماعيلية</option>
                                            <option value="المنوفية">المنوفية</option>
                                            <option value="المنيا">المنيا</option>
                                            <option value="القليوبية">القليوبية</option>
                                            <option value="الوادي الجديد">الوادي الجديد</option>
                                            <option value="السويس">السويس</option>
                                            <option value="اسوان">اسوان</option>
                                            <option value="اسيوط">اسيوط</option>
                                            <option value="بني سويف">بني سويف</option>
                                            <option value="بورسعيد">بورسعيد</option>
                                            <option value="دمياط">دمياط</option>
                                            <option value="الشرقية">الشرقية</option>
                                            <option value="جنوب سيناء">جنوب سيناء</option>
                                            <option value="كفر الشيخ">كفر الشيخ</option>
                                            <option value="مطروح">مطروح</option>
                                            <option value="الأقصر">الأقصر</option>
                                            <option value="قنا">قنا</option>
                                            <option value="شمال سيناء">شمال سيناء</option>
                                            <option value="سوهاج">سوهاج</option>                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="billing-info mb-20">
                                    <label>{{ __('checkout.phone') }} <abbr class="required" title="{{ __('checkout.required') }}">*</abbr></label>
                                    <input type="text">
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="billing-info mb-20">
                                    <label>{{ __('checkout.email') }} </label>
                                    <input type="text">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="your-order-area">
                        <h3>{{ __('checkout.order') }}</h3>
                        <div class="your-order-wrap">
                            <div class="your-order-info-wrap">
                                <div class="your-order-title">
                                    <h4>{{ __('checkout.product') }} <span>{{ __('checkout.subtotal') }}</span></h4>
                                </div>
                                <div class="your-order-product">
                                    <ul>
                                        @foreach($items as $item)
                                        <li>{{ $item->quantity }} × {{ $item->name }} <span>{{ $item->price * $item->quantity }} {{ __('checkout.currency') }}</span></li>
                                        @endforeach
                                    </ul>
                                </div>
                                <div class="your-order-subtotal">
                                    <h3>{{ __('checkout.products_price') }} <span>{{$totalPrice}} {{ __('checkout.currency') }}</span></h3>
                                </div>
                                <div class="your-order-shipping">
                                    <span>{{ __('checkout.shipping') }}</span>
                                    <ul>
                                        <li><input type="radio" name="shipping" value="info" checked="checked"><label>{{ __('checkout.free_shipping') }}</label></li>
                                        <li><input type="radio" name="shipping" value="info"><label>{{ __('checkout.flat_rate') }}: <span>100.00 {{ __('checkout.currency') }}</span></label></li>
                                        <li><input type="radio" name="shipping" value="info"><label>{{ __('checkout.local_pickup') }}: <span>120.00 {{ __('checkout.currency') }}</span></label></li>
                                    </ul>
                                </div>
                                <div class="your-order-total">
                                    <h3>{{ __('checkout.total') }} <span>{{$totalPrice}} {{ __('checkout.currency') }}</span></h3>
                                </div>
                            </div>
                            <div class="payment-method">

                                <div class="pay-top sin-payment">
                                    <input id="payment-method-5" class="input-radio" type="radio" value="cheque" name="payment_method">
                                    <label for="payment-method-5">{{ __('checkout.cash_on_delivery') }}</label>
                                    <div class="payment-box payment_method_bacs">
                                        <p>{{ __('checkout.cash_on_delivery_desc') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="Place-order">
                            <a class="place-order-btn btn btn-block btn-lg" href="route">{{ __('checkout.place_order') }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--== End Checkout Area Wrapper ==-->

@endsection
@push('styles')
    <style>
            .select-style .select-active {
                padding-inline-start: 36px;
                line-height: 38px;
            }

            .place-order-btn{
                background-color: #f379a7;
                color: #fff;
                margin-top: 15px;
                text-align: center;
                width: 100%
            }
    </style>
@endpush
