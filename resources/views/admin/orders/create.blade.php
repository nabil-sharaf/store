@extends('admin.layouts.app')

@section('page-title')
    الطلبات
@endsection

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
            <h3 class="card-title float-left">إضافة طلب جديد</h3>
        </div>
        <form class="form-horizontal" action="{{ route('admin.orders.store') }}" method="POST"
              enctype="multipart/form-data" dir="rtl">
            @csrf
            <div class="card-body">
                <div class="form-group row">
                    <label for="inputUser" class="col-sm-2 control-label">المستخدم</label>
                    <div class="col-sm-10">
                        <select class=" select2 form-control @error('user_id') is-invalid @enderror" id="inputUser"
                                name="user_id" required>
                            <option value="">اختر مستخدم</option>
                            @foreach($users as $user)
                                @php
                                    $user_type = $user->customer_type == 'goomla'?'جملة':'قطاعي';
                                    $user_vip = $user->is_vip ?' - vip':'';
                                @endphp
                                @if($user->status)
                                    <option
                                        data-vip-discount="{{ $user->is_vip ? $user->discount : 0 }}"
                                        data-user-type="{{$user->customer_type}}"
                                            value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                        {{ $user->name .' ('. $user_type .' '. $user_vip . ' )'}}
                                    </option>
                                @endif
                            @endforeach
                        </select>
                        @error('user_id')
                        <div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="form-group row">
                    <label for="inputProduct" class="col-sm-2 control-label">المنتجات</label>
                    <div class="col-sm-10">
                        <div id="product-fields">
                            <div class="product-field card mb-3">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <h5 class="card-title mb-0 product-number">المنتج 1</h5>
                                    </div>
                                    <div class="form-group">
                                        <label for="product-select-0">اختر المنتج</label>
                                        <select id="product-select-0" class="select2 form-control product-select"
                                                name="products[0][id]" required>
                                            <option value="">اختر منتج</option>
                                            @foreach($products as $product)
                                                @php
                                                    $retailPrice = $product->price; // السعر القطاعي
                                                    $wholesalePrice = $product->goomla_price; // السعر الجملة
                                                    $discountType = $product->discount->discount_type ?? null; // نوع الخصم (percentage أو fixed)
                                                    $discountValue = $product->discount->discount ?? 0; // قيمة الخصم

                                                    // حساب الخصم على السعر القطاعي
                                                    if ($discountType === 'percentage') {
                                                        $retailDiscountAmount = ($retailPrice * $discountValue) / 100;
                                                    } elseif ($discountType === 'fixed') {
                                                        $retailDiscountAmount = $discountValue;
                                                    } else {
                                                        $retailDiscountAmount = 0;
                                                    }
                                                    $retailFinalPrice = $retailPrice - $retailDiscountAmount; // السعر النهائي بعد الخصم

                                                    // حساب الخصم على سعر الجملة
                                                    if ($discountType === 'percentage') {
                                                        $wholesaleDiscountAmount = ($wholesalePrice * $discountValue) / 100;
                                                    } elseif ($discountType === 'fixed') {
                                                        $wholesaleDiscountAmount = $discountValue;
                                                    } else {
                                                        $wholesaleDiscountAmount = 0;
                                                    }
                                                    $wholesaleFinalPrice = $wholesalePrice - $wholesaleDiscountAmount; // السعر النهائي بعد الخصم
                                                @endphp
                                                <option value="{{ $product->id }}"
                                                        data-price-retail="{{ $product->price }}"
                                                        data-price-wholesale="{{ $product->goomla_price }}"
                                                        data-quantity="{{ $product->quantity }}"
                                                        data-discount-type="{{ $discountType }}"
                                                        data-discount-value="{{ $discountValue }}">
                                                                            {{ $product->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="product-quantity-0">الكمية المطلوبة</label>
                                                <input type="number" id="product-quantity-0"
                                                       name="products[0][quantity]"
                                                       class="form-control product-quantity" required min="1">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="product-current-quantity-0">الكمية المتاحة للمنتج</label>
                                                <input type="text" id="product-current-quantity-0"
                                                       class="form-control product-current-quantity" readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="product-price-0">السعر </label>
                                                <input type="text" id="product-price-0"
                                                       class="form-control product-price" readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="product-discount-0">الخصم </label>
                                                <input type="text" id="product-discount-0"
                                                       class="form-control product-discount" readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="product-discountPrice-0">السعر بعد الخصم </label>
                                                <input type="text" id="product-discountPrice-0"
                                                       class="form-control product-discountPrice" readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="product-total-0">الإجمالي</label>
                                                <input type="text" id="product-total-0"
                                                       class="form-control product-total" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="text-left">
                            <button type="button" class="btn btn-secondary mt-2" id="addProductButton">إضافة منتج آخر
                            </button>
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="totalBeforeDiscount" class="col-sm-2 control-label">إجمالي الأوردر</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="totalBeforeDiscount" readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="inputDiscountPercentage" class="col-sm-2 control-label">نسبة الخصم</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="inputDiscountPercentage" readonly>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="inputDiscountAmount" class="col-sm-2 control-label">قيمة الخصم</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="inputDiscountAmount" readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="inputTotalOrder" class="col-sm-2 control-label">الإجمالي بعد الخصم</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="inputTotalOrder" readonly>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-info float-right">حفظ البيانات</button>
                </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function () {
            var productCount = 0;
            var vipDiscountRate = 0; // ستقوم بتحديثه بناءً على العميل


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
                initializeSelect2();
            });

            $('#product-fields').on('input', '.product-quantity', function () {
                updateTotal($(this).closest('.card-body'));
            });

            function updateTotal(productBody) {
                var price = parseFloat(productBody.find('.product-discountPrice').val()) || 0;
                var quantity = parseInt(productBody.find('.product-quantity').val()) || 0;
                var total = price * quantity;
                productBody.find('.product-total').val(total.toFixed(2));
                updateTotalOrder();
            }

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

            function updateDiscount() {
                var userId = $('#inputUser').val();
                var totalBeforeDiscount = calculateTotalBeforeDiscount();
                var url = "{{route('admin.get-user-discount', ':id')}}".replace(':id', userId);

                if (userId) {
                    $.ajax({
                        url: url,
                        type: 'GET',
                        success: function (response) {
                            var discountPercentage = response.discount;
                            if (discountPercentage && discountPercentage > 0) {
                                var discountAmount = (totalBeforeDiscount * discountPercentage) / 100;
                                var totalAfterDiscount = totalBeforeDiscount - discountAmount;

                                $('#inputDiscountPercentage').val(discountPercentage + ' % ');
                                $('#inputDiscountAmount').val(discountAmount.toFixed(2));
                                $('#inputTotalOrder').val(totalAfterDiscount.toFixed(2));
                            } else {
                                $('#inputDiscountPercentage').val('لا يوجد خصم');
                                $('#inputDiscountAmount').val('0');
                                $('#inputTotalOrder').val(totalBeforeDiscount.toFixed(2));
                            }
                        },
                        error: function (xhr, status, error) {
                            console.error("Error fetching discount: " + error);
                            $('#inputDiscountPercentage').val('خطأ في جلب الخصم');
                            $('#inputDiscountAmount').val('0');
                            $('#inputTotalOrder').val(totalBeforeDiscount.toFixed(2));
                        }
                    });
                } else {
                    $('#inputDiscountPercentage').val('لا يوجد خصم');
                    $('#inputDiscountAmount').val('0');
                    $('#inputTotalOrder').val(totalBeforeDiscount.toFixed(2));
                }
            }

            function calculateTotalBeforeDiscount() {
                var total = 0;
                $('.product-total').each(function () {
                    total += parseFloat($(this).val()) || 0;
                });
                return total;
            }

            // تحديث الخصم عند تغيير المستخدم
            $('#inputUser').change(function () {

                // الحصول على نسبة الخصم من الـ data attribute
                vipDiscountRate = parseFloat($(this).find(':selected').data('vip-discount')) || 0;
                $('#inputDiscountPercentage').val(vipDiscountRate.toFixed(2) + '%');

                updateUserTypeAndPrices();
                // updateDiscount();
                updateTotalOrder(); // إعادة حساب إجمالي الطلب بعد التحديث

            });

            function updateUserTypeAndPrices() {
                var userType = $('#inputUser').find(':selected').data('user-type'); // جلب نوع العميل
                $('.product-select').each(function () {
                    var selectedProduct = $(this).find(':selected');
                    var priceRetail = selectedProduct.data('price-retail');
                    var priceWholesale = selectedProduct.data('price-wholesale');
                    var discountType = selectedProduct.data('discount-type');  // نوع الخصم
                    var discountValue = selectedProduct.data('discount-value');  // قيمة الخصم

                    var productBody = $(this).closest('.card-body');

                    // تحديد السعر بناءً على نوع المستخدم
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

                    // تحديث الحقول الخاصة بالسعر والخصم
                    productBody.find('.product-price').val(price);  // السعر الأصلي
                    productBody.find('.product-discountPrice').val(finalPrice);  // السعر النهائي بعد الخصم

                    // تحديث إجمالي السعر
                    updateTotal(productBody);
                });
            }


            // تعديل دالة updateTotalOrder
            function updateTotalOrder() {
                var totalOrder = 0;
                $('.product-total').each(function () {
                    totalOrder += parseFloat($(this).val()) || 0;
                });
                var discountAmount = (totalOrder * vipDiscountRate) / 100;
                $('#totalBeforeDiscount').val(totalOrder.toFixed(2));
                $('#inputDiscountPercentage').val(vipDiscountRate.toFixed(2) + '%');
                $('#inputDiscountAmount').val(discountAmount.toFixed(2));
                $('#inputTotalOrder').val((totalOrder - discountAmount).toFixed(2));
                //updateDiscount();  // نضيف هذا السطر لتحديث الخصم بعد تحديث الإجمالي
            }
        });
    </script>
@endpush
@push('styles')
    <style>
        label {
            font-size: 13px;
        }
    </style>
@endpush
