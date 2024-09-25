@extends('front.layouts.app')
@section('title','اتمام الطلب')

@section('content')

@php
if(auth()->user()){
    $address = auth()->user()->address;
}
 @endphp

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
                            <form id="applyCouponForm" method="POST">
                                @csrf
                                <p>{{ __('checkout.have_coupon') }}</p>
                                <input type="text" name="promo_code" id="couponCode" placeholder="{{ __('checkout.apply_coupon') }}">
                                <button type="submit" id="applyCouponButton">{{ __('checkout.apply_coupon') }}</button>
                            </form>
                            <div id="couponMessage"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-7">
                    <form id="checkout-form" method="POST" action="{{ route('checkout.store') }}">
                        @csrf
                        <div class="billing-info-wrap">
                            <h3>{{ __('checkout.billing_details') }}</h3>
                            <div class="row">
                                <div class="col-12">
                                    <div class="billing-info mb-20">
                                        <label>{{ __('checkout.full_name') }} <abbr class="required" title="{{ __('checkout.required') }}">*</abbr></label>
                                        <input type="text" name="full_name" value="{{ old('full_name', auth()->user() ? $address?->full_name : '') }}" placeholder="{{__('checkout.full_name_placeholder')}}">
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="billing-info mb-20">
                                        <label>{{ __('checkout.address') }} <abbr class="required" title="{{ __('checkout.required') }}">*</abbr></label>
                                        <input class="billing-address" name="address" placeholder="{{ __('checkout.address_placeholder') }}" type="text" value="{{ old('address', auth()->user() ? $address?->address : '') }}">
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="billing-info mb-20">
                                        <label>{{ __('checkout.city') }} <abbr class="required" title="{{ __('checkout.required') }}">*</abbr></label>
                                        <input type="text" name="city" placeholder="{{ __('checkout.city_placeholder') }}" value="{{ old('city', auth()->user() ? $address?->city : '') }}">
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="billing-select mb-20">
                                        <label>{{ __('checkout.state') }} <abbr class="required" title="{{ __('checkout.required') }}">*</abbr></label>
                                        <div class="select-style ">
                                            <select class=" select-active" name="state">
                                                <option value="" disabled selected>اختر اسم محافظتك</option>
                                                <option value="القاهرة" {{ old('state', auth()->user() ? $address?->state : '') == 'القاهرة' ? 'selected' : '' }}>القاهرة</option>
                                                <option value="الجيزة" {{ old('state', auth()->user() ? $address?->state : '') == 'الجيزة' ? 'selected' : '' }}>الجيزة</option>
                                                <option value="الإسكندرية" {{ old('state', auth()->user() ? $address?->state : '') == 'الإسكندرية' ? 'selected' : '' }}>الإسكندرية</option>
                                                <option value="الدقهلية" {{ old('state', auth()->user() ? $address?->state : '') == 'الدقهلية' ? 'selected' : '' }}>الدقهلية</option>
                                                <option value="البحر الأحمر" {{ old('state', auth()->user() ? $address?->state : '') == 'البحر الأحمر' ? 'selected' : '' }}>البحر الأحمر</option>
                                                <option value="البحيرة" {{ old('state', auth()->user() ? $address?->state : '') == 'البحيرة' ? 'selected' : '' }}>البحيرة</option>
                                                <option value="الفيوم" {{ old('state', auth()->user() ? $address?->state : '') == 'الفيوم' ? 'selected' : '' }}>الفيوم</option>
                                                <option value="الغربية" {{ old('state', auth()->user() ? $address?->state : '') == 'الغربية' ? 'selected' : '' }}>الغربية</option>
                                                <option value="الإسماعيلية" {{ old('state', auth()->user() ? $address?->state : '') == 'الإسماعيلية' ? 'selected' : '' }}>الإسماعيلية</option>
                                                <option value="المنوفية" {{ old('state', auth()->user() ? $address?->state : '') == 'المنوفية' ? 'selected' : '' }}>المنوفية</option>
                                                <option value="المنيا" {{ old('state', auth()->user() ? $address?->state : '') == 'المنيا' ? 'selected' : '' }}>المنيا</option>
                                                <option value="القليوبية" {{ old('state', auth()->user() ? $address?->state : '') == 'القليوبية' ? 'selected' : '' }}>القليوبية</option>
                                                <option value="الوادي الجديد" {{ old('state', auth()->user() ? $address?->state : '') == 'الوادي الجديد' ? 'selected' : '' }}>الوادي الجديد</option>
                                                <option value="الشرقية" {{ old('state', auth()->user() ? $address?->state : '') == 'الشرقية' ? 'selected' : '' }}>الشرقية</option>
                                                <option value="سوهاج" {{ old('state', auth()->user() ? $address?->state : '') == 'سوهاج' ? 'selected' : '' }}>سوهاج</option>
                                                <option value="أسوان" {{ old('state', auth()->user() ? $address?->state : '') == 'أسوان' ? 'selected' : '' }}>أسوان</option>
                                                <option value="أسيوط" {{ old('state', auth()->user() ? $address?->state : '') == 'أسيوط' ? 'selected' : '' }}>أسيوط</option>
                                                <option value="بني سويف" {{ old('state', auth()->user() ? $address?->state : '') == 'بني سويف' ? 'selected' : '' }}>بني سويف</option>
                                                <option value="بورسعيد" {{ old('state', auth()->user() ? $address?->state : '') == 'بورسعيد' ? 'selected' : '' }}>بورسعيد</option>
                                                <option value="دمياط" {{ old('state', auth()->user() ? $address?->state : '') == 'دمياط' ? 'selected' : '' }}>دمياط</option>
                                                <option value="السويس" {{ old('state', auth()->user() ? $address?->state : '') == 'السويس' ? 'selected' : '' }}>السويس</option>
                                                <option value="الأقصر" {{ old('state', auth()->user() ? $address?->state : '') == 'الأقصر' ? 'selected' : '' }}>الأقصر</option>
                                                <option value="قنا" {{ old('state', auth()->user() ? $address?->state : '') == 'قنا' ? 'selected' : '' }}>قنا</option>
                                                <option value="مطروح" {{ old('state', auth()->user() ? $address?->state : '') == 'مطروح' ? 'selected' : '' }}>مطروح</option>
                                                <option value="شمال سيناء" {{ old('state', auth()->user() ? $address?->state : '') == 'شمال سيناء' ? 'selected' : '' }}>شمال سيناء</option>
                                                <option value="جنوب سيناء" {{ old('state', auth()->user() ? $address?->state : '') == 'جنوب سيناء' ? 'selected' : '' }}>جنوب سيناء</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="billing-info mb-20">
                                        <label>{{ __('checkout.phone') }} <abbr class="required" title="{{ __('checkout.required') }}">*</abbr></label>
                                        <input type="text" name="phone" value="{{ old('phone', auth()->user() ? $address?->phone : '') }}">
                                    </div>
                                </div>
                            </div>
                        </div>

                    </form>
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
{{--                                <div class="your-order-shipping">--}}
{{--                                    <span>{{ __('checkout.shipping') }}</span>--}}
{{--                                    <ul>--}}
{{--                                        <li><input type="radio" name="shipping" value="free" checked="checked"><label>{{ __('checkout.free_shipping') }}</label></li>--}}
{{--                                        <li><input type="radio" name="shipping" value="flat_rate"><label>{{ __('checkout.flat_rate') }}: <span>100.00 {{ __('checkout.currency') }}</span></label></li>--}}
{{--                                        <li><input type="radio" name="shipping" value="local_pickup"><label>{{ __('checkout.local_pickup') }}: <span>120.00 {{ __('checkout.currency') }}</span></label></li>--}}
{{--                                    </ul>--}}
{{--                                </div>--}}

                                <div class="your-order-subtotal" id ="copoun-discount" style="display:none">
                                    <h3>قيمة خصم الكوبون <span id="copoun-discount-value"></span></h3>
                                </div>

                                @if(Auth::user() && Auth::user()->isVip() )
                                    <div class="your-order-subtotal" id ="vip-discount" >
                                        <h3>قيمة خصم vip : <span id="vip-discount-value"> {{$vipDiscount}}</span></h3>
                                    </div>
                                @endif

                                <div class="your-order-total">
                                    <h3>{{ __('checkout.total') }} <span id="totalAfterDiscount" class="total-amount">{{$totalPrice - $vipDiscount}} {{ __('checkout.currency') }}</span></h3>
                                </div>
                            </div>
{{--                            <div class="payment-method">--}}
{{--                                <div class="pay-top sin-payment">--}}
{{--                                    <input id="payment-method-5" class="input-radio" type="radio" value="cod" name="payment_method">--}}
{{--                                    <label for="payment-method-5">{{ __('checkout.cash_on_delivery') }}</label>--}}
{{--                                    <div class="payment-box payment_method_bacs">--}}
{{--                                        <p>{{ __('checkout.cash_on_delivery_desc') }}</p>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}
                            <div class="Place-order">
                                <button type="submit" id="submit-order" class="place-order-btn btn btn-block btn-lg">{{ __('checkout.place_order') }}</button>
                            </div>
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
                margin-top: 30px;
                text-align: center;
                width: 100%
            }
            .your-order-total {
                border-top: 2px solid #ddd; /* خط فاصل في الأعلى */
                padding-top: 15px; /* مساحة فوق النص */
                margin-top: 20px; /* مساحة فوق هذا القسم */
            }

            .total-title {
                font-size: 20px; /* حجم الخط */
                font-weight: bold; /* جعل الخط سميك */
                color: #333; /* لون النص */
            }

            .total-amount {
                font-size: 24px !important;/* حجم أكبر لمبلغ الإجمالي */
                font-weight: bold !important; /* جعل الخط سميك */
                color: #e67e22 !important; /* لون مميز للمبلغ */
            }
    </style>
@endpush

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#applyCouponForm').on('submit', function(e) {
                e.preventDefault();

                var couponCode = $('#couponCode').val();
                var orderTotal = {{$totalPrice}} ; // إجمالي الطلب
                var userId = {{ auth()->check() ? auth()->user()->id : 'null' }}; // معرف المستخدم الحالي

                $.ajax({
                    url: '{{ route("checkout.promo") }}',
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    data: {
                        promo_code: couponCode,
                        total_order: orderTotal,
                        user_id: userId
                    },
                    success: function(data) {
                        if (data.success) {
                            $('#couponMessage').html('<p style="color: green;">' + data.success + '</p>');

                            $('#applyCouponButton').prop('disabled', true).css({
                                'background-color': '#ccc',
                                'color': '#666',
                                'cursor': 'not-allowed'
                            });

                            // جعل حقل الكوبون readonly
                            $('#couponCode').prop('readonly', true);

                            $('#copoun-discount').show();
                            $('#copoun-discount-value').text(parseFloat(data.discount).toFixed(2));
                            // يمكنك تحديث إجمالي الطلب بعد الخصم هنا مثلاً:
                            var newTotal = (orderTotal - data.discount - {{$vipDiscount}}).toFixed(2);
                            $('#totalAfterDiscount').text(newTotal);
                        }else if (data.error) {
                            $('#couponMessage').html('<p style="color: red;">' + data.error + '</p>');
                        }
                    },
                    error: function(xhr) {
                        toastr.error('حدث خطأ تأكد من الكوبون وحاول مرة أخرى')

                    }
                });
            });

            $('#submit-order').on('click', function(event) {
                event.preventDefault();

                // جمع بيانات فورم الطلب
                var orderData = $('#checkout-form').serialize();

                // جمع بيانات فورم كوبون الخصم
                var couponData = $('#applyCouponForm').serialize();

                // دمج بيانات الفورمين
                var formData = orderData + '&' + couponData;

                var submitButton = $('#submit-order');
                submitButton.prop('disabled', true).text('جاري الإرسال...');

                // إرسال البيانات المجمعة عبر AJAX
                $.ajax({
                    url: $('#checkout-form').attr('action'), // الرابط الموجه للفورم
                    method: 'POST',
                    data: formData,
                    success: function(response) {
                        if (response.success) {

                            window.location.href =response.route;
                        } else {
                            toastr('خطأ: ' + response.message); // عرض رسالة الخطأ
                            submitButton.prop('disabled', false).text('اتمام الطلب');

                        }
                    },
                    error: function(response) {
                        if (response.responseJSON && response.responseJSON.message) {
                            toastr.error(response.responseJSON.message);
                        } else {
                            alert('حدث خطأ غير متوقع.');
                        }
                        submitButton.prop('disabled', false).text('اتمام الطلب');

                    }
                });
            });

        });
    </script>
@endpush
