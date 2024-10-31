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
                        <h2 class="title">
                            @if(session()->has('editing_order_id'))
                                {{ __('checkout.page_title_edit') }}
                            @else
                                {{ __('checkout.page_title') }}
                            @endif
                        </h2>
                        <div class="bread-crumbs"><a
                                href="{{ route('home.index') }}"> {{ __('checkout.home') }} </a><span
                                class="breadcrumb-sep"> {{ __('checkout.breadcrumb_sep') }} </span><span
                                class="active"> {{ __('checkout.checkout_page') }}</span></div>
                    </div>
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
                        <h2 class="title">
                            @if(session()->has('editing_order_id'))
                                {{ __('checkout.page_title_edit') .' رقم :'. session()->get('editing_order_id') }}
                            @else
                                {{ __('checkout.page_title') }}
                            @endif
                        </h2>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="checkout-coupon-wrap mb-65 mb-md-40">
                        @if(auth()->user()?->customer_type == 'regular')
                        <p class="cart-page-title"><i
                                class="ion-ios-pricetag-outline"></i> {{ __('checkout.have_coupon') }} <a
                                class="checkout-coupon-active" href="#/">{{ __('checkout.apply_coupon') }}</a></p>
                            <div class="checkout-coupon-content">
                                <form id="applyCouponForm" method="POST">
                                    @csrf
                                    <p>{{ __('checkout.have_coupon') }}</p>
                                    <input type="text" name="promo_code" id="couponCode"
                                           placeholder="{{ __('checkout.apply_coupon') }}">
                                    <button type="submit"
                                            id="applyCouponButton">{{ __('checkout.apply_coupon') }}</button>
                                </form>
                                <div id="couponMessage"></div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-7">
                    <form id="checkout-form" method="POST"
                          action=" @if(session()->has('editing_order_id')) {{ route('checkout.update',session()->get('editing_order_id')) }} @else {{ route('checkout.store') }} @endif">
                        @csrf
                        @if(session()->has('editing_order_id'))
                            @method('PUT')
                        @endif
                        <div class="billing-info-wrap">
                            <h3>{{ __('checkout.billing_details') }}</h3>
                            <div class="row">
                                <div class="col-12">
                                    <div class="billing-info mb-20">
                                        <label>{{ __('checkout.full_name') }} <abbr class="required"
                                                                                    title="{{ __('checkout.required') }}">*</abbr></label>
                                        <input type="text" name="full_name"
                                               value="{{ old('full_name', auth()->user() ? $address?->full_name : '') }}"
                                               placeholder="{{__('checkout.full_name_placeholder')}}">
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="billing-info mb-20">
                                        <label>{{ __('checkout.address') }} <abbr class="required"
                                                                                  title="{{ __('checkout.required') }}">*</abbr></label>
                                        <input class="billing-address" name="address"
                                               placeholder="{{ __('checkout.address_placeholder') }}" type="text"
                                               value="{{ old('address', auth()->user() ? $address?->address : '') }}">
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="billing-info mb-20">
                                        <label>{{ __('checkout.city') }} <abbr class="required"
                                                                               title="{{ __('checkout.required') }}">*</abbr></label>
                                        <input type="text" name="city"
                                               placeholder="{{ __('checkout.city_placeholder') }}"
                                               value="{{ old('city', auth()->user() ? $address?->city : '') }}">
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="billing-select mb-20">
                                        <label>{{ __('checkout.state') }} <abbr class="required"
                                                                                title="{{ __('checkout.required') }}">*</abbr></label>
                                        <div class="select-style ">
                                            <select class=" select-active" name="state"
                                                    data-user-state="{{ auth()->check() ? auth()->user()?->address?->state : '' }}">
                                                <option value="" disabled selected>اختر اسم محافظتك</option>
                                                @foreach($states as $state)
                                                    <option
                                                        value="{{$state->state}}" {{ old('state', auth()->user() ? $address?->state : '') == $state->state ? 'selected' : '' }}>{{$state->state}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="billing-info mb-20">
                                        <label>{{ __('checkout.phone') }} <abbr class="required"
                                                                                title="{{ __('checkout.required') }}">*</abbr></label>
                                        <input type="text" name="phone"
                                               value="{{ old('phone', auth()->user() ? $address?->phone : '') }}">
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
                                            @if($item->attributes['free_quantity'] > 0 )
                                                <li> {{ $item->quantity }} × {{ $item->name }} &nbsp; + <span
                                                        class="free-quantity-number"> {{$item->attributes['free_quantity']}} <span
                                                            class="free-quantity">  قطعة مجانا </span></span>
                                                    <span>{{ $item->price * $item->quantity }} {{ __('checkout.currency') }}</span>
                                                </li>
                                            @else
                                                <li>{{ $item->quantity }} × {{ $item->name }}
                                                    <span>{{ $item->price * $item->quantity }} {{ __('checkout.currency') }}</span>
                                                </li>
                                            @endif
                                        @endforeach
                                    </ul>
                                </div>
                                <div class="your-order-subtotal">
                                    <h3>{{ __('checkout.products_price') }}
                                        <span>{{$totalPrice}} {{ __('checkout.currency') }}</span></h3>
                                </div>
                                {{--                                <div class="your-order-shipping">--}}
                                {{--                                    <span>{{ __('checkout.shipping') }}</span>--}}
                                {{--                                    <ul>--}}
                                {{--                                        <li><input type="radio" name="shipping" value="free" checked="checked"><label>{{ __('checkout.free_shipping') }}</label></li>--}}
                                {{--                                        <li><input type="radio" name="shipping" value="flat_rate"><label>{{ __('checkout.flat_rate') }}: <span>100.00 {{ __('checkout.currency') }}</span></label></li>--}}
                                {{--                                        <li><input type="radio" name="shipping" value="local_pickup"><label>{{ __('checkout.local_pickup') }}: <span>120.00 {{ __('checkout.currency') }}</span></label></li>--}}
                                {{--                                    </ul>--}}
                                {{--                                </div>--}}

                                <div class="your-order-subtotal" id="copoun-discount" style="display:none">
                                    <h3>قيمة خصم الكوبون <span id="copoun-discount-value"></span></h3>
                                </div>

                                @if(Auth::user() && Auth::user()->isVip() )
                                    <div class="your-order-subtotal" id="vip-discount">
                                        <h3>قيمة خصم vip : <span id="vip-discount-value"> {{$vipDiscount}}</span></h3>
                                    </div>
                                @endif

                                <div class="your-order-total">
                                    <h3>{{ __('checkout.total') }} <span id="totalAfterDiscount"
                                                                         class="total-amount">{{ floatval($totalPrice)}} {{ __('checkout.currency') }}</span>
                                    </h3>
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
                                @if(session()->has('editing_order_id'))
                                    <P class="copoun-notice"> * إذا كان لديك كوبون خصم ادخله قبل تأكيد التعديل </P>
                                    <button type="submit" id="submit-order"
                                            class="place-order-btn btn btn-block btn-lg copoun-notice-button">{{ __('checkout.edit_order') }}</button>
                                @else
                                    <button type="submit" id="submit-order"
                                            class="place-order-btn btn btn-block btn-lg">{{ __('checkout.place_order') }}</button>
                                @endif
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

        .place-order-btn {
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
            font-size: 24px !important; /* حجم أكبر لمبلغ الإجمالي */
            font-weight: bold !important; /* جعل الخط سميك */
            color: #e67e22 !important; /* لون مميز للمبلغ */
        }

        .copoun-notice {
            color: red;
            font-size: 13px;
            margin-top: 10px !important;
            margin-bottom: 5px !important;
            font-weight: bold;
        }

        .copoun-notice-button {
            margin-top: 10px;
        }
    </style>
@endpush

@push('scripts')
    <script>
        $(document).ready(function () {

            // دالة لحساب الإجمالي بعد الخصومات والشحن
            function recalculateTotal(orderTotal, vipDiscount, couponDiscount, shippingCost = 0) {
                var total = orderTotal;

                if (vipDiscount) {
                    total -= vipDiscount;
                }

                if (couponDiscount) {
                    total -= couponDiscount;
                }

                // إضافة تكلفة الشحن
                total += shippingCost;

                return total.toFixed(2);
            }

            const stateSelect = $('select[name="state"]');
            // تحقق مما إذا كان المستخدم مسجلاً ولديه محافظة مسجلة
            const userState = stateSelect.data('user-state'); // افترض أن الحقل يحتفظ بالمحافظة المخزنة

            // -------------------  تطبيق الكوبون --------------------
            $('#applyCouponForm').on('submit', function (e) {
                e.preventDefault();

                var couponCode = $('#couponCode').val();
                var orderTotal = {{$totalPrice}}; // إجمالي الطلب
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
                    success: function (data) {
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

                            var vipDiscount = {{$vipDiscount}} || 0;
                            var couponDiscount = data.discount || 0;

                            var selectedState = $('select[name="state"]').val();
                            if (selectedState) {
                                calculateShippingCost(selectedState);
                            }

                            // حساب الإجمالي الجديد باستخدام الدالة
                            var newTotal = recalculateTotal(orderTotal, vipDiscount, couponDiscount);
                            $('#totalAfterDiscount').text(newTotal);
                        } else if (data.error) {
                            $('#couponMessage').html('<p style="color: red;">' + data.error + '</p>');
                            $('#couponCode').val('');
                        }
                    },
                    error: function (xhr) {
                        toastr.error('حدث خطأ تأكد من الكوبون وحاول مرة أخرى');
                    }
                });
            });

            // -------------------  إرسال الطلب --------------------
            $('#submit-order').on('click', function (event) {
                event.preventDefault();

                // جمع بيانات فورم الطلب
                var orderData = $('#checkout-form').serialize();

                // جمع بيانات فورم كوبون الخصم
                var couponData = $('#applyCouponForm').serialize();

                // دمج بيانات الفورمين
                var formData = orderData + '&' + couponData;

                var submitButton = $('#submit-order');
                submitButton.prop('disabled', true).text('جاري الإرسال...');

                var method = @if(session()->has('editing_order_id')) "PUT"
                @else "POST" @endif;

                // إرسال البيانات المجمعة عبر AJAX
                $.ajax({
                    url: $('#checkout-form').attr('action'), // الرابط الموجه للفورم
                    method: method,
                    data: formData,
                    success: function (response) {
                        if (response.success) {
                            window.location.href = response.route;
                        } else {
                            toastr.error('خطأ: ' + response.message); // عرض رسالة الخطأ
                            submitButton.prop('disabled', false).text('اتمام الطلب');
                        }
                    },
                    error: function (response) {
                        if (response.responseJSON && response.responseJSON.message) {
                            toastr.error(response.responseJSON.message);
                            console.log(response);
                        } else {
                            alert('حدث خطأ حاول مرة أخرى.');
                        }
                        @if(isset($order?->id))
                        submitButton.prop('disabled', false).text('تأكيد التعديل');
                        @else
                        submitButton.prop('disabled', false).text('اتمام الطلب');
                        @endif
                    }
                });
            });

            // -------------------  حساب تكلفة الشحن --------------------

            // const stateSelect = $('select[name="state"]');

            const totalAfterDiscount = $('#totalAfterDiscount');

            const totalAmount = parseFloat(totalAfterDiscount.text());

            // إنشاء عنصر لتكلفة الشحن بالتنسيق المناسب
            const shippingCostElement = $('<div class="your-order-subtotal"><h3>تكلفة الشحن: <span id="shipping-cost">---</span></h3></div>');
            totalAfterDiscount.closest('.your-order-info-wrap').find('.your-order-total').before(shippingCostElement);

            // وظيفة لحساب تكلفة الشحن
            function calculateShippingCost(state) {
                if (!state) {
                    $('#shipping-cost').text('غير متوفر');
                    return;
                }

                $.ajax({
                    url: "{{route('checkout.getShippingCost', ':state')}}".replace(':state', state),
                    method: 'GET',
                    success: function (response) {
                        const shippingCost = response.shipping_cost;
                        if (shippingCost == 0 || !shippingCost) {
                            $('#shipping-cost').text(' شحن مجاني');
                        } else {
                            $('#shipping-cost').text(shippingCost + ' {{ __("checkout.currency") }}');
                        }

                        // تحديث إجمالي المبلغ بعد إضافة تكلفة الشحن
                        var updatedTotal = recalculateTotal(totalAmount, {{$vipDiscount}} || 0, parseFloat($('#copoun-discount-value').text()) || 0, parseFloat(shippingCost));
                        totalAfterDiscount.html(updatedTotal + ' <span>{{ __("checkout.currency") }}</span>');
                    },
                    error: function () {
                        $('#shipping-cost').text('خطأ في جلب تكلفة الشحن');
                    }
                });
            }

            if (userState) {
                // حساب تكلفة الشحن بناءً على المحافظة المسجلة
                calculateShippingCost(userState);
                recalculateTotal();
            }

            // حدث عند تغيير المحافظة
            stateSelect.change(function () {
                const state = $(this).val();
                calculateShippingCost(state);
                recalculateTotal();
            });

        });


    </script>
@endpush
