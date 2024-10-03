@extends('admin.layouts.app')
@section('page-title', 'تعديل الطلب')

@section('content')
    @if ($errors->any())
        <div class="alert alert-danger pt-2 pb-0">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <div class="card card-info">
        <div class="card-header">
            <h3 class="card-title float-left">تعديل الطلب</h3>
        </div>
        <form class="form-horizontal" id="orderForm" novalidate dir="rtl">
            @csrf
            <div class="card-body">

                <div id="initialFormSection">
                    <!-- User selection -->
                    <div class="form-group row">

                        <label for="inputUser" class="col-sm-2 control-label">المستخدم</label>
                        <div class="col-sm-10">
                            <select class="select2 form-control @error('user_id') is-invalid @enderror" id="inputUser"
                                    name="user_id">
                                @php
                                    $user = $order->user;
                                    $promocode = $order->promocode;
                                        $user_type = $user?->customer_type == 'goomla' ? 'جملة' : 'قطاعي';
                                        $user_vip = $user?->isVip() ? ' - vip' : '';
                                @endphp
                                @if($user?->status)
                                    <option
                                        data-vip-discount="{{ $user->isVip() ? $user->discount : 0 }}"
                                        data-user-type="{{ $user->customer_type }}"
                                        value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                        {{ $user->name . ' (' . $user_type . $user_vip . ')' }}
                                    </option>
                                @else
                                    <option value=""  data-user-type="">Guest</option>
                                @endif
                            </select>
                            @error('user_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Products -->
                    <div class="form-group row">
                        <label class="col-sm-2 control-label">المنتجات</label>
                        <div class="col-sm-10">
                            <div id="product-fields">
                                @foreach($order->orderDetails as $index => $orderDetail)
                                    <div class="product-field card mb-3">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <h5 class="card-title mb-0 product-number">المنتج {{ $index + 1 }}</h5>
                                                @if($index > 0)
                                                    <button type="button" class="btn btn-danger btn-sm remove-product">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                @endif
                                            </div>
                                            <div class="form-group">
                                                <label for="product-select-{{ $index }}">اختر المنتج</label>
                                                <select id="product-select-{{ $index }}"
                                                        class="select2 form-control product-select"
                                                        name="products[{{ $index }}][id]" required>
                                                    <option value="">اختر منتج</option>
                                                    @foreach($products as $product)

                                                        <option value="{{ $product->id }}"
                                                                data-price-retail="{{ $product->price }}"
                                                                data-price-wholesale="{{ $product->goomla_price }}"
                                                                data-quantity="{{ $product->quantity }}"
                                                                data-discount-type="{{ $product->discount->discount_type ?? null }}"
                                                                data-discount-value="{{ $product->discount->discount ?? 0 }}"
                                                            {{ $orderDetail->product_id == $product->id ? 'selected' : '' }}>
                                                            {{ $product->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="row">
                                                <!-- Product Quantity -->
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="product-quantity-{{ $index }}">الكمية
                                                            المطلوبة</label>
                                                        <input type="number" id="product-quantity-{{ $index }}"
                                                               name="products[{{ $index }}][quantity]"
                                                               class="form-control product-quantity"
                                                               value="{{ $orderDetail->product_quantity }}"
                                                               required min="1">
                                                        <span class="free-quantity">
                                                            @if($orderDetail->free_quantity > 0)
                                                             + عدد <span class="free-quantity-number">{{$orderDetail->free_quantity}}</span> قطعة هدية
                                                            @endif
                                                        </span>
                                                    </div>
                                                </div>

                                                <!-- Current Quantity Available -->
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="product-current-quantity-{{ $index }}">الكمية
                                                            المتاحة للمنتج</label>
                                                        <input type="text" id="product-current-quantity-{{ $index }}"
                                                               class="form-control product-current-quantity"
                                                               value="{{ $products->find($orderDetail->product_id)->quantity }}"
                                                               readonly>
                                                    </div>
                                                </div>

                                                <!-- Product Price -->
                                                @php
                                                    $product_price = $order->user?->customer_type == 'goomla'?$orderDetail->product->goomla_price : $orderDetail->product->price;
                                                @endphp
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="product-price-{{ $index }}">السعر</label>
                                                        <input type="text" id="product-price-{{ $index }}"
                                                               class="form-control product-price"
                                                               value="{{$product_price}}"
                                                               readonly>
                                                    </div>
                                                </div>

                                                <!-- Product Discount -->
                                                <div class="col-md-4">
                                                    @php
                                                        $discountValue = 0;
                                                        $product = $orderDetail->product;
                                                        $discount = $product->discount ?? null;

                                                        // استخدام السعر الأصلي للمنتج بدلاً من سعر تفاصيل الطلب
                                                        $originalPrice = $product->price;

                                                        if ($discount) {
                                                            if ($discount->discount_type === 'percentage') {
                                                                $discountValue = $originalPrice * ($discount->discount / 100);
                                                            } elseif ($discount->discount_type === 'fixed') {
                                                                $discountValue = $discount->discount;
                                                            }
                                                        }

                                                        // تقريب قيمة الخصم إلى رقمين عشريين
                                                        $discountValue = round($discountValue, 2);
                                                    @endphp

                                                    <div class="form-group">
                                                        <label for="product-discount-{{ $index }}">الخصم</label>
                                                        <input type="text" id="product-discount-{{ $index }}"
                                                               class="form-control product-discount"
                                                               value="{{ $discountValue }}" readonly>
                                                    </div>
                                                </div>

                                                <!-- Price After Discount -->
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="product-discountPrice-{{ $index }}">السعر بعد
                                                            الخصم</label>
                                                        <input type="text" id="product-discountPrice-{{ $index }}"
                                                               class="form-control product-discountPrice"
                                                               value="{{ $product_price - $discountValue }}"
                                                               readonly>
                                                    </div>
                                                </div>

                                                <!-- Total -->
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="product-total-{{ $index }}">الإجمالي</label>
                                                        <input type="text" id="product-total-{{ $index }}"
                                                               class="form-control product-total"
                                                               value="{{ ($product_price-$discountValue) * $orderDetail->product_quantity }}"
                                                               readonly>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="text-left">
                                <button type="button" class="btn btn-secondary mt-2" id="addProductButton">إضافة منتج
                                    آخر
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row" id="coupon-group-id"
                         style="display: {{$promocode?->code ? '' : 'none'}}">
                        <label for="promo_code" class="col-sm-2 col-form-label">كوبون الخصم</label>
                        <div class="col-sm-10">
                            <div class="row">
                                <div class="col-lg-8 col-md-8 col-sm-12 mb-2">
                                    <input class="form-control" type="text" id="promo_code" name="promo_code"
                                           placeholder="أدخل كود الخصم إذا وجد" value="{{$promocode?->code}}" readonly>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-12 d-sm-block d-lg-inline-block">
                                    <button type="button" id="applyCouponButton"
                                            class="btn btn-primary w-100 mt-sm-2 mt-lg-0" disabled>
                                        تطبيق الكوبون بعد التعديلات
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>


                    <!-- Total Before Discount -->
                    <div class="form-group row">
                        <label for="totalBeforeDiscount" class="col-sm-2 control-label">إجمالي الأوردر قبل الخصم</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="totalBeforeDiscount"
                                   name="total_before_discount"
                                   value="{{ old('total_before_discount', $order->total_price) }}" readonly>
                        </div>
                    </div>

                    <!-- Discount vip Percentage -->
                    <div class="form-group row">
                        <label for="inputDiscountPercentage" class="col-sm-2 control-label">نسبة خصم vip</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="inputDiscountPercentage"
                                   value="{{ old('inputDiscountPercentage', $order->user?->is_vip ? $order->user->discount . ' %' : 'لا يوجد'  ) }}"
                                   readonly>
                        </div>
                    </div>

                    <!-- Discount vip Amount -->
                    <div class="form-group row">
                        <label for="inputDiscountAmount" class="col-sm-2 control-label">قيمة خصم vip</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="inputDiscountAmount"
                                   value="{{ old('inputDiscountAmount', $order->vip_discount )}}" readonly>
                        </div>
                    </div>

                    <!-- Discount copoun Amount -->
                    <div class="form-group row">
                        <label for="copounDiscountAmount" class="col-sm-2 control-label">قيمة خصم الكوبون</label>

                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="copounDiscountAmount"
                                   value="{{ old('inputDiscountAmount', $order->promo_discount )}}"
                                   data-copoun-code="{{$promocode?->code}}"
                                   readonly>
                        </div>
                    </div>
                </div>


                <div id="addressSection" style="display: none">
                    <div class="form-group">
                        <label for="full_name">الاسم بالكامل</label>
                        <input type="text" class="form-control" id="full_name" name="full_name"
                               value="{{ old('full_name', $address->full_name ?? null)}}" required>
                    </div>

                    <div class="form-group">
                        <label for="inputPhone">رقم التليفون</label>
                        <input type="tel" class="form-control" id="inputPhone" name="phone"
                               value="{{ old('phone', $address->phone ?? null)}}" required>
                    </div>

                    <div class="form-group">
                        <label for="inputAddress">العنوان</label>
                        <input type="text" class="form-control" id="inputAddress" name="address"
                               value="{{ old('address', $address->address ?? null)}}" required>
                    </div>

                    <div class="form-group">
                        <label for="inputCity">المدينة</label>
                        <input type="text" class="form-control" id="inputCity" name="city"
                               value="{{ old('city', $address->city ?? null)}}" required>
                    </div>
                    <div class="form-group">
                        <label for="state">المحافظة</label>
                        <select class="form-control" name="state" data-user-state="{{ $user->address?->state ?? '' }}">
                            <option value="" disabled selected>اختر اسم محافظتك</option>
                            @foreach($states as $state)
                                <option value="{{$state->state}}" {{ old('state', $user->address->state ?? '') == $state->state ? 'selected' : '' }}>{{$state->state}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Total After Discount -->
                <div class="form-group   ">
                    <label for="inputTotalOrder"   > الاجمالي بعد الخصم  </label>
                        <input type="text" class=" form-control" id="inputTotalOrder" name="total_price"
                               value="{{ old('total_price', $order->total_after_discount) }}" readonly
                               data-order-id="{{$order->id}}">

                </div>

                <div class="form-group " id= 'shipping-cost-div' style = 'display: none;'>
                    <label for="shipping_cost">تكلفة الشحن</label>
                    <input type="text" class="form-control" id="shipping_cost" readonly>
                </div>

            </div>

            <div class="card-footer">
                <button type="button" id="showAddressButton" class="btn btn-info btn-block">أكمل الطلب</button>
                <button type="button" id="goBackButton" class="btn btn-secondary btn-block" style="display: none;">
                    رجوع
                </button>
                <button type="submit" id="submitOrderButton" class="btn btn-success btn-block" style="display: none;">
                    تأكيد الطلب
                </button>

            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function () {
            // هذا الكود يضيف الـ CSRF token لجميع الطلبات AJAX
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            var productCount = {{ $order->orderDetails->count() - 1 }};
            var vipDiscountRate = parseFloat($('#inputUser').find(':selected').data('vip-discount')) || 0;


            $('#addProductButton').click(function () {
                addProductField();
            });

            function addProductField() {
                productCount++;
                var newField = `
    <div class="product-field card mb-3">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h5 class="card-title mb-0 product-number">المنتج ${productCount + 1}</h5>
                <button type="button" class="btn btn-danger btn-sm remove-product">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
            <div class="form-group">
                <label for="product-select-${productCount}">اختر المنتج</label>
                <select id="product-select-${productCount}" class="select2 form-control product-select" name="products[${productCount}][id]" required>
                    <option value="">اختر منتج</option>
                    @foreach($products as $product)
                <option value="{{ $product->id }}"
                    data-price-retail="{{ $product->price }}"
                    data-price-wholesale="{{ $product->goomla_price }}"
                    data-discount-type="{{ $product->discount->discount_type ?? '' }}"
                    data-discount-value="{{ $product->discount->discount ?? 0 }}"
                    data-quantity="{{ $product->quantity }}">{{ $product->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="product-quantity-${productCount}">الكمية المطلوبة</label>
                        <input type="number" id="product-quantity-${productCount}" name="products[${productCount}][quantity]" class="form-control product-quantity" required min="1" value="1">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="product-current-quantity-${productCount}">الكمية المتاحة للمنتج</label>
                        <input type="text" id="product-current-quantity-${productCount}" class="form-control product-current-quantity" readonly>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="product-price-${productCount}">السعر </label>
                        <input type="text" id="product-price-${productCount}" class="form-control product-price" readonly>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="product-discount-${productCount}">الخصم</label>
                        <input type="text" id="product-discount-${productCount}" class="form-control product-discount" readonly>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="product-discountPrice-${productCount}">السعر بعد الخصم</label>
                        <input type="text" id="product-discountPrice-${productCount}" class="form-control product-discountPrice" readonly>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="product-total-${productCount}">الإجمالي</label>
                        <input type="text" id="product-total-${productCount}" class="form-control product-total" readonly>
                    </div>
                </div>
            </div>
        </div>
    </div>
    `;
                $('#product-fields').append(newField);
                initializeSelect2();
                updateProductNumbers();
                updateTotalOrder();
            }

            function updateProductNumbers() {
                $('.product-field').each(function (index) {
                    $(this).find('.product-number').text('المنتج ' + (index + 1));
                    $(this).find('select').attr('name', `products[${index}][id]`);
                    $(this).find('.product-quantity').attr('name', `products[${index}][quantity]`);
                });
            }

            $('#product-fields').on('click', '.remove-product', function () {
                $(this).closest('.product-field').remove();
                updateProductNumbers();
                $('#copounDiscountAmount').val('0 '); // تحديث الخصم الجديد
                $('#applyCouponButton').removeAttr('disabled');
                updateTotalOrder();
            });

            $('#product-fields').on('change', '.product-select', function () {
                var selectedProduct = $(this).find(':selected');
                var priceRetail = selectedProduct.data('price-retail');
                var priceWholesale = selectedProduct.data('price-wholesale');
                var discountType = selectedProduct.data('discount-type');  // نوع الخصم
                var discountValue = selectedProduct.data('discount-value');  // قيمة الخصم

                var quantity = selectedProduct.data('quantity');
                var productBody = $(this).closest('.card-body');
                var userType = $('#inputUser').find(':selected').data('user-type'); // الحصول على نوع العميل من البيانات المخزنة

                var price = userType === 'goomla' ? priceWholesale : priceRetail;

                // حساب الخصم
                var discountAmount = 0;
                if (discountType === 'percentage') {
                    discountAmount = (price * discountValue) / 100;
                } else if (discountType === 'fixed') {
                    discountAmount = discountValue;
                }

                // حساب السعر النهائي بعد الخصم
                var finalPrice = price - discountAmount;

                productBody.find('.product-current-quantity').val(quantity);
                productBody.find('.product-price').val(price);
                productBody.find('.product-discount').val(discountAmount);
                productBody.find('.product-discountPrice').val(finalPrice);

                updateTotal(productBody);
                $('#copounDiscountAmount').val('0 '); // تحديث الخصم الجديد
                $('#applyCouponButton').removeAttr('disabled');

                initializeSelect2();

                // Get free Product Quantity
                var $productField = $(this).closest('.product-field');
                getFreeQuantity($productField,function(freeQuantity) {
                    // هنا تستطيع استخدام الكمية المجانية كما تريد
                    $productField.find('.free-quantity').html(
                        freeQuantity > 0 ? `+ عدد <span class="free-quantity-number">${freeQuantity}</span> قطعة مجاني` : ''
                    );
                });

            });

            $('#product-fields').on('input', '.product-quantity', function () {

                updateTotal($(this).closest('.card-body'));

                // Get free Product Quantity
                var $productField = $(this).closest('.product-field');
                getFreeQuantity($productField,function(freeQuantity) {
                    // هنا تستطيع استخدام الكمية المجانية كما تريد
                    $productField.find('.free-quantity').html(
                        freeQuantity > 0 ? `+ عدد <span class="free-quantity-number">${freeQuantity}</span> قطعة مجاني` : ''
                    );
                });



            });

            function updateTotal(productBody) {
                var price = parseFloat(productBody.find('.product-discountPrice').val()) || 0;
                var quantity = parseInt(productBody.find('.product-quantity').val()) || 0;
                var total = price * quantity;
                productBody.find('.product-total').val(total.toFixed(2));

                $('#copounDiscountAmount').val('0 '); // تحديث الخصم الجديد
                $('#applyCouponButton').removeAttr('disabled');

                updateTotalOrder();
            }


            // تعديل دالة updateTotalOrder
            function updateTotalOrder() {
                var totalOrder = 0;
                $('.product-total').each(function () {
                    totalOrder += parseFloat($(this).val()) || 0;
                });
                var discountAmount = (totalOrder * vipDiscountRate) / 100;
                var copounAmount = $('#copounDiscountAmount').val();

                $('#totalBeforeDiscount').val(totalOrder.toFixed(2));
                $('#inputDiscountPercentage').val(vipDiscountRate.toFixed(2) + '%');
                $('#inputDiscountAmount').val(discountAmount.toFixed(2));
                $('#inputTotalOrder').val((totalOrder - discountAmount - copounAmount).toFixed(2));
            }

            // هذا الكود يتم تنفيذه بعد تعديل الطلب، سواء بحذف منتج أو تغيير الكميات
            $('#applyCouponButton').click(function () {
                var couponCode = $('#copounDiscountAmount').data('copoun-code');
                var totalOrder = $('#totalBeforeDiscount').val(); // إجمالي الطلب الجديد
                var userId = $('#inputUser').val();
                if (userId) {
                    $.ajax({
                        url: "{{ route('admin.update-promo-code') }}",
                        method: 'POST',
                        data: {
                            promo_code: couponCode,
                            total_order: totalOrder,
                            user_id: userId,
                            _token: '{{ csrf_token() }}'
                        },
                        success: function (response) {
                            if (response.discount !== undefined) {
                                $('#copounDiscountAmount').val((response.discount).toFixed(2)); // تحديث الخصم الجديد
                                toastr.success(response.success);
                                $('#applyCouponButton').attr('disabled', 'disabled');
                                updateTotalOrder(); // تحديث إجمالي الطلب بعد الخصم
                            } else {
                                toastr.error('حدث خطأ غير متوقع. حاول مرة أخرى.');
                            }

                        },
                        error: function (xhr) {
                            let error = JSON.parse(xhr.responseText);
                            if (error.error) {
                                toastr.error(error.error);
                                console.log(couponCode)
                            } else {
                                toastr.error('حدث خطأ غير متوقع. حاول مرة أخرى.');
                            }
                        }
                    });
                } else {
                    $('#copounDiscountAmount').val('0 '); // تحديث الخصم الجديد

                }

            });

            $('#orderForm').on('submit', function (e) {
                e.preventDefault();

                let orderId = $('#inputTotalOrder').data('order-id');


                let hasError = false;
                let errorMessage = '';

                // التحقق من أن هناك منتج واحد على الأقل مضاف
                let productSelected = false;

                $('.product-select').each(function () {
                    if ($(this).val() !== '') {
                        productSelected = true;
                    }
                });

                if (!productSelected) {
                    hasError = true;
                    errorMessage += 'يجب إضافة منتج واحد على الأقل.\n';
                }

                // التحقق من الكمية لكل منتج
                $('.product-select').each(function (index) {
                    const quantity = $(`#product-quantity-${index}`).val();
                    const selectedProduct = $(this).val();

                    // إذا تم اختيار المنتج، تأكد من أن الكمية صحيحة
                    if (selectedProduct && (!quantity || quantity <= 0)) {
                        hasError = true;
                        errorMessage += `الرجاء إدخال كمية صحيحة للمنتج رقم ${index + 1}.\n`;
                    }
                });

                if (hasError) {
                    // إظهار رسالة الخطأ
                    toastr.error(errorMessage);
                } else {

                    let form = this;

                    // استخدام FormData لجمع بيانات النموذج
                    let formData = new FormData(form);

                    // إضافة token CSRF إلى formData
                    let csrfToken = $('meta[name="csrf-token"]').attr('content');
                    formData.append('_token', csrfToken);

                    // إضافة _method للتعامل مع PUT request
                    formData.append('_method', 'PUT');

                    // التأكد من أن formData يحتوي على البيانات الصحيحة
                    // for (var pair of formData.entries()) {
                    //     console.log(pair[0] + ': ' + pair[1]);
                    // }

                    $.ajax({
                        type: 'POST',
                        url: "{{ route('admin.orders.update',':orderId') }}".replace(':orderId', orderId),  // نفس المسار القديم لتخزين البيانات
                        data: formData,
                        processData: false,
                        contentType: false,
                        headers: {
                            'X-CSRF-TOKEN': csrfToken
                        },
                        beforeSend: function () {
                            // عرض حالة التحميل (يمكن استخدام أي عنصر لتحميل الرسوم)
                            $('#submitButton').attr('disabled', true);  // تعطيل الزر أثناء الإرسال
                        },
                        success: function (response) {
                            // إذا تم الحفظ بنجاح
                            if (response.success) {
                                toastr.success(response.message);  // عرض رسالة النجاح

                                // إعادة توجيه المستخدم إلى صفحة الـ index بعد نجاح الحفظ
                                window.location.href = '{{ route('admin.orders.index') }}';
                            }
                        },
                        error: function (xhr) {
                            // إذا حدث خطأ في التحقق
                            if (xhr.status === 422) {
                                let errors = xhr.responseJSON.errors;

                                // حذف جميع رسائل الأخطاء السابقة
                                $('.invalid-feedback').remove();
                                $('.is-invalid').removeClass('is-invalid');

                                // عرض الأخطاء الجديدة
                                $.each(errors, function (key, value) {
                                    let inputElement = $('[name="' + key + '"]');
                                    inputElement.addClass('is-invalid');

                                    // إذا لم يكن هناك عنصر لعرض رسالة الخطأ، نضيفه بعد عنصر الإدخال
                                    if (!inputElement.next('.invalid-feedback').length) {
                                        inputElement.after('<div class="invalid-feedback">' + value[0] + '</div>');
                                    }
                                });


                                // عرض رسالة الخطأ العامة المستلمة من الـ Controller
                                toastr.error(xhr.responseJSON.message || 'هناك بعض الأخطاء في البيانات.');
                            } else {
                                toastr.error('حدث خطأ غير متوقع.');  // عرض رسالة الخطأ غير المتوقعة
                            }
                        },
                        complete: function () {
                            // إزالة حالة التحميل بعد انتهاء الطلب (سواء نجاح أو فشل)
                            $('#submitButton').attr('disabled', false);
                        }
                    });
                }

            });


            function initializeSelect2() {
                $('.select2').select2({
                    theme: 'bootstrap4',
                    width: '100%',
                    language: {
                        noResults: function () {
                            return "لا توجد نتائج";
                        }
                    }
                });
            }


 // --------------------------------  حساب تكلفة الشحن --------------------------------------------

            const stateSelect = $('select[name="state"]');


            // وظيفة لحساب تكلفة الشحن
            function calculateShippingCost(state) {
                if (!state) {
                    $('#shipping_cost').val('غير متوفر');
                    return;
                }

                $.ajax({
                    url: "{{route('admin.checkout.getShippingCost',':state')}}".replace(':state',state),
                    method: 'GET',
                    success: function(response) {
                        const shippingCost = response.shipping_cost;
                        if (shippingCost == 0 || !shippingCost) {
                            $('#shipping_cost').val(' شحن مجاني');
                            alert('0000')
                        }
                        else {
                            $('#shipping_cost').val(shippingCost + ' جنيه  ');
                        }
                    },

                    error: function() {
                        $('#shipping_cost').val('خطأ في جلب تكلفة الشحن');
                    }
                });
            }

            // تحقق مما إذا كان المستخدم مسجلاً ولديه محافظة مسجلة
            const userState = stateSelect.data('user-state'); // افترض أن الحقل يحتفظ بالمحافظة المخزنة
            if (userState) {
                // حساب تكلفة الشحن بناءً على المحافظة المسجلة
                calculateShippingCost(userState);
            }

            // حدث عند تغيير المحافظة
            stateSelect.change(function() {
                const state = $(this).val();
                calculateShippingCost(state);


            });

            // الحصول على الكمية المجانية من المنتج اذا كان عليه عرض واستخدام الكولباك
            function getFreeQuantity($productField,callback){

                var userType = $('#inputUser').find(':selected').data('user-type'); // الحصول على نوع العميل من البيانات المخزنة
                var productId = $productField.find('.product-select').val(); // ID المنتج
                var quantity = $productField.find('.product-quantity').val(); // الكمية المدخلة

                if (productId && quantity) {
                    $.ajax({
                        url: "{{route('admin.orders.free-quantity')}}",
                        method: 'GET',
                        data : {
                            productId: productId,
                            quantity: quantity,
                            customer_type: userType,
                        },
                        success: function (response) {
                            // نمرر الكمية المجانية للـ callback
                            if (callback && typeof callback === 'function') {
                                callback(response.free_quantity);
                            }
                        },
                        error: function (xhr) {
                            console.log(xhr)
                            // alert('حدث خطأ أثناء جلب الكمية المجانية');
                        }
                    });
                }
            }
        });

        document.addEventListener('DOMContentLoaded', function () {
            const initialFormSection = document.getElementById('initialFormSection');
            const addressSection = document.getElementById('addressSection');
            const showAddressButton = document.getElementById('showAddressButton');
            const submitOrderButton = document.getElementById('submitOrderButton');
            const goBackButton = document.getElementById('goBackButton');
            const ShippingDiv = document.getElementById('shipping-cost-div');


            // الانتقال لأعلى الصفحة
            function scrollToTop() {
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth' // تمرير سلس إلى أعلى الصفحة
                });
            }

            showAddressButton.addEventListener('click', function () {
                initialFormSection.style.display = 'none';
                addressSection.style.display = 'block';
                showAddressButton.style.display = 'none';
                submitOrderButton.style.display = 'block';
                goBackButton.style.display = 'block'; // عرض زر الرجوع
                ShippingDiv.style.display = 'block';  // أظهار تكلفة الشحن



                scrollToTop(); // الانتقال لأعلى الصفحة عند الضغط على "أكمل الطلب"
            });

            goBackButton.addEventListener('click', function () {
                initialFormSection.style.display = 'block';
                addressSection.style.display = 'none';
                showAddressButton.style.display = 'block';
                submitOrderButton.style.display = 'none';
                goBackButton.style.display = 'none'; // إخفاء زر الرجوع عند الرجوع
                ShippingDiv.style.display = 'none';  // اخفاء تكلفة الشحن

                scrollToTop(); // الانتقال لأعلى الصفحة عند الضغط على "رجوع"
            });
        });

    </script>
@endpush

@push('styles')
    <style>
        .custom-select {
            padding-right: 30px !important;
            background-position: left 0.75rem center !important;
            background-size: 16px 12px !important;
        }


    </style>
@endpush
