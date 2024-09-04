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
                                    <label>{{ __('checkout.first_name') }} <abbr class="required" title="{{ __('checkout.required') }}">*</abbr></label>
                                    <input type="text">
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="billing-info mb-20">
                                    <label>{{ __('checkout.last_name') }} <abbr class="required" title="{{ __('checkout.required') }}">*</abbr></label>
                                    <input type="text">
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="billing-info mb-20">
                                    <label>{{ __('checkout.company_name') }} <abbr class="required" title="{{ __('checkout.required') }}">*</abbr></label>
                                    <input type="text">
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="billing-select mb-20">
                                    <label>{{ __('checkout.country') }} <abbr class="required" title="{{ __('checkout.required') }}">*</abbr></label>
                                    <div class="select-style">
                                        <select class="select-active">
                                            <option>{{ __('checkout.bangladesh') }}</option>
                                            <option>{{ __('checkout.bahrain') }}</option>
                                            <option>{{ __('checkout.azerbaijan') }}</option>
                                            <option>{{ __('checkout.barbados') }}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="billing-info mb-20">
                                    <label>{{ __('checkout.street_address') }} <abbr class="required" title="{{ __('checkout.required') }}">*</abbr></label>
                                    <input class="billing-address" placeholder="{{ __('checkout.street_address') }}" type="text">
                                    <input placeholder="{{ __('checkout.apartment') }}" type="text">
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="billing-info mb-20">
                                    <label>{{ __('checkout.city') }} <abbr class="required" title="{{ __('checkout.required') }}">*</abbr></label>
                                    <input type="text">
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="billing-select mb-20">
                                    <label>{{ __('checkout.state') }} <abbr class="required" title="{{ __('checkout.required') }}">*</abbr></label>
                                    <div class="select-style">
                                        <select class="select-active">
                                            <option>{{ __('checkout.choose_option') }}</option>
                                            <option>{{ __('checkout.barguna') }}</option>
                                            <option>{{ __('checkout.bandarban') }}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="billing-info mb-20">
                                    <label>{{ __('checkout.postcode') }} <abbr class="required" title="{{ __('checkout.required') }}">*</abbr></label>
                                    <input type="text">
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
                                    <label>{{ __('checkout.email') }} <abbr class="required" title="{{ __('checkout.required') }}">*</abbr></label>
                                    <input type="text">
                                </div>
                            </div>
                        </div>
                        <div class="checkout-account">
                            <input class="checkout-toggle" type="checkbox">
                            <span>{{ __('checkout.ship_to_different') }}</span>
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
                                        <li>هودي براذر باللون الرمادي × 4 <span>140.00 {{ __('checkout.currency') }}</span></li>
                                        <li>تيشيرت انجو ذا ريست × 1 <span>39.59 {{ __('checkout.currency') }}</span></li>
                                    </ul>
                                </div>
                                <div class="your-order-subtotal">
                                    <h3>{{ __('checkout.subtotal') }} <span>617.59 {{ __('checkout.currency') }}</span></h3>
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
                                    <h3>{{ __('checkout.total') }} <span>617.59 {{ __('checkout.currency') }}</span></h3>
                                </div>
                            </div>
                            <div class="payment-method">
                                <div class="pay-top sin-payment">
                                    <input id="payment_method_1" class="input-radio" type="radio" value="cheque" checked="checked" name="payment_method">
                                    <label for="payment_method_1">{{ __('checkout.bank_transfer') }}</label>
                                    <div class="payment-box payment_method_bacs">
                                        <p>{{ __('checkout.bank_transfer_desc') }}</p>
                                    </div>
                                </div>
                                <div class="pay-top sin-payment">
                                    <input id="payment-method-2" class="input-radio" type="radio" value="cheque" name="payment_method">
                                    <label for="payment-method-2">{{ __('checkout.cheque_payment') }}</label>
                                    <div class="payment-box payment_method_bacs">
                                        <p>{{ __('checkout.cheque_payment_desc') }}</p>
                                    </div>
                                </div>
                                <div class="pay-top sin-payment">
                                    <input id="payment-method-3" class="input-radio" type="radio" value="cheque" name="payment_method">
                                    <label for="payment-method-3">{{ __('checkout.cod') }}</label>
                                    <div class="payment-box payment_method_bacs">
                                        <p>{{ __('checkout.cod_desc') }}</p>
                                    </div>
                                </div>
                                <div class="pay-top sin-payment">
                                    <input id="payment-method-4" class="input-radio" type="radio" value="cheque" name="payment_method">
                                    <label for="payment-method-4">{{ __('checkout.paypal') }}</label>
                                    <div class="payment-box payment_method_bacs">
                                        <p>{{ __('checkout.paypal_desc') }}</p>
                                    </div>
                                </div>
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
                            <a class="place-order-btn" href="#">{{ __('checkout.place_order') }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--== End Checkout Area Wrapper ==-->

@endsection
