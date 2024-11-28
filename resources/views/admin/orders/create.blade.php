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
            <h3 class="card-title " style="float:none">إضافة طلب جديد</h3>
        </div>
        <form id="orderForm" novalidate enctype="multipart/form-data" dir="rtl">
            @csrf
            <div class="card-body">

                <div id="initialFormSection">
                    <div class="form-group">
                        <label for="inputUser">اختر العميل</label>
                        <select class="form-control select2 @error('user_id') is-invalid @enderror" id="inputUser"
                                name="user_id">
                            <option value="" data-user-type="">
                                Guest- زائر
                            </option>

                            @foreach($users as $user)
                                @php
                                    $user_type = $user->customer_type == 'goomla' ? 'جملة' : 'قطاعي';
                                    $user_vip = $user->isVip() ? ' - vip' : '';
                                @endphp
                                @if($user->status)
                                    <option
                                        data-vip-discount="{{ $user->isVip() ? $user->discount : 0 }}"
                                        data-user-type="{{ $user->customer_type }}"
                                        value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                        {{ $user->name . ' (' . $user_type . $user_vip . ')' }}
                                    </option>
                                @endif
                            @endforeach
                        </select>
                        @error('user_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="inputProduct">المنتجات</label>
                        <div id="product-fields">
                            <div class="product-field card mb-3">
                                <div class="card-body">
                                    <h5 class="card-title mb-3 product-number">المنتج 1</h5>
                                    <div class="form-group">
                                        <label for="product-select-0">اختر المنتج</label>
                                        <select id="product-select-0" class="form-control select2 product-select"
                                                name="products[0][id]" required>
                                            <option value="">اختر منتج</option>
                                            @foreach($products as $product)
                                                <option value="{{ $product->id }}"
                                                        data-price-retail="{{ $product->price }}"
                                                        data-price-wholesale="{{ $product->goomla_price }}"
                                                        data-quantity="{{ $product->quantity }}"
                                                        data-discount-type="{{ $product->discount->discount_type ?? null }}"
                                                        data-discount-value="{{ $product->discount->discount ?? 0 }}"
                                                        data-has-variants="{{ $product->variants->isNotEmpty() ? 'true' : 'false' }}"
                                                >
                                                    {{ $product->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="variant-select-0">اختر الفاريانت</label>
                                        <select id="variant-select-0" class="form-control select2 product-variant"
                                                name="products[0][variant_id]" disabled>
                                            <option value="" selected disabled>اختر الفاريانت</option>
                                        </select>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 col-lg-4 mb-3">
                                            <label for="product-quantity-0">الكمية المطلوبة</label>
                                            <input type="number" id="product-quantity-0" name="products[0][quantity]"
                                                   class="form-control product-quantity" required min="1" value="1">
                                            <span class="free-quantity"></span>
                                        </div>
                                        <div class="col-md-6 col-lg-4 mb-3">
                                            <label for="product-current-quantity-0">الكمية المتاحة للمنتج</label>
                                            <input type="text" id="product-current-quantity-0"
                                                   class="form-control product-current-quantity" readonly>
                                        </div>
                                        <div class="col-md-6 col-lg-4 mb-3">
                                            <label for="product-price-0">السعر</label>
                                            <input type="text" id="product-price-0" class="form-control product-price"
                                                   readonly>
                                        </div>
                                        <div class="col-md-6 col-lg-4 mb-3">
                                            <label for="product-discount-0">الخصم</label>
                                            <input type="text" id="product-discount-0"
                                                   class="form-control product-discount" readonly>
                                        </div>
                                        <div class="col-md-6 col-lg-4 mb-3">
                                            <label for="product-discountPrice-0">السعر بعد الخصم</label>
                                            <input type="text" id="product-discountPrice-0"
                                                   class="form-control product-discountPrice" readonly>
                                        </div>
                                        <div class="col-md-6 col-lg-4 mb-3">
                                            <label for="product-total-0">الإجمالي</label>
                                            <input type="text" id="product-total-0" class="form-control product-total"
                                                   readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button type="button" class="btn btn-secondary mt-2" id="addProductButton">إضافة منتج آخر
                    </button>
                </div>

                <div class="form-group" id="coupon-group-id" style="display: none">
                    <label for="promo_code">كوبون الخصم</label>
                    <div class="input-group coupon-input-group">
                        <input type="text" id="promo_code" name="promo_code" class="form-control"
                               placeholder="أدخل كود الخصم إذا وجد" readonly>
                        <div class="input-group-append">
                            <button type="button" id="applyCouponButton" class="btn btn-primary ms-2" disabled>تطبيق
                                الكوبون
                            </button>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="totalBeforeDiscount">إجمالي الأوردر</label>
                    <input type="text" class="form-control" id="totalBeforeDiscount" readonly>
                </div>
                <div class="form-group">
                    <label for="inputDiscountPercentage">نسبة خصم VIP</label>
                    <input type="text" class="form-control" id="inputDiscountPercentage" readonly>
                </div>
                <div class="form-group">
                    <label for="inputDiscountAmount">قيمة خصم VIP</label>
                    <input type="text" class="form-control" id="inputDiscountAmount" readonly>
                </div>
                <div class="form-group">
                    <label for="copounDiscountAmount">قيمة خصم الكوبون</label>
                    <input type="text" class="form-control" id="copounDiscountAmount" readonly>
                </div>

                <div id="addressSection" style="display: none;">
                    <div class="form-group">
                        <label for="full_name">الاسم بالكامل</label>
                        <input type="text" class="form-control" id="full_name" name="full_name"
                               {{ old('full_name', $user->address?->full_name )}} required>
                    </div>

                    <div class="form-group">
                        <label for="inputPhone">رقم التليفون</label>
                        <input type="tel" class="form-control" id="inputPhone" name="phone"
                               {{ old('phone', $user->address?->phone )}} required>
                    </div>

                    <div class="form-group">
                        <label for="inputAddress">العنوان</label>
                        <input type="text" class="form-control" id="inputAddress" name="address"
                               {{ old('address', $user->address?->address )}} required>
                    </div>

                    <div class="form-group">
                        <label for="inputCity">المدينة</label>
                        <input type="text" class="form-control" id="inputCity" name="city"
                               {{ old('city', $user->address?->city )}} required>
                    </div>
                    <div class="form-group">
                        <label for="state">المحافظة</label>
                        <select class="form-control" name="state" data-user-state="{{ $user->address?->state ?? '' }}">
                            <option value="" disabled selected>اختر اسم محافظتك</option>
                            @foreach($states as $state)
                                <option
                                    value="{{$state->state}}" {{ old('state', $user->address->state ?? '') == $state->state ? 'selected' : '' }}>{{$state->state}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="inputTotalOrder">إجمالي الأورد بعد الخصم</label>
                    <input type="text" class="form-control" id="inputTotalOrder" readonly>
                </div>

                <div class="form-group " id='shipping-cost-div' style='display: none;'>
                    <label for="shipping_cost">تكلفة الشحن</label>
                    <input type="text" class="form-control" id="shipping_cost" readonly>
                </div>

                <div class="card-footer">
                    <button type="button" id="showAddressButton" class="btn btn-info btn-block">أكمل الطلب</button>
                    <button type="button" id="goBackButton" class="btn btn-secondary btn-block" style="display: none;">
                        رجوع
                    </button>
                    <button type="submit" id="submitOrderButton" class="btn btn-success btn-block"
                            style="display: none;">
                        تأكيد الطلب
                    </button>
                </div>
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
                    data-has-variants="{{ $product->variants->isNotEmpty() ? 'true' : 'false' }}"
                    data-quantity="{{ $product->available_quantity }}">{{ $product->name }}
                </option>
                    @endforeach
                </select>
            </div>
                                              <div class="form-group">
                                        <label for="variant-select-${productCount}">اختر الفاريانت</label>
                                        <select id="variant-select-${productCount}" class="form-control select2 product-variant"
                                                name="products[${productCount}][variant_id]" disabled>
                                            <option value="" selected disabled>اختر الفاريانت</option>
                                        </select>
                                    </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="product-quantity-${productCount}">الكمية المطلوبة</label>
                        <input type="number" id="product-quantity-${productCount}" name="products[${productCount}][quantity]" class="form-control product-quantity" required min="1" value="1">
                          <span class="free-quantity"></span>
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
                    // تحديث name للمنتج فقط (باستخدام class محددة)
                    $(this).find('.product-select').attr('name', `products[${index}][id]`);

                    // تحديث name للفاريانت بشكل صحيح
                    $(this).find('.product-variant').attr('name', `products[${index}][variant_id]`);
                    $(this).find('.product-quantity').attr('name', `products[${index}][quantity]`);
                });
            }

            $('#product-fields').on('click', '.remove-product', function () {
                $(this).closest('.product-field').remove();

                updateProductTotal($(this).closest('.card-body'));
                $('#promo_code').removeAttr('readonly');
                $('#applyCouponButton').removeAttr('disabled');
                $('#copounDiscountAmount').val('0');

                updateProductNumbers();
                updateTotalOrder();
            });

            $('#product-fields').on('change', '.product-select', function () {
                var selectedProduct = $(this).find(':selected');
                var productId = selectedProduct.val();
                var productBody = $(this).closest('.card-body');

                // إعداد قائمة الفاريانتس
                var variantSelect = productBody.find('.product-variant');
                variantSelect.prop('disabled', true).html('<option value="">اختر الفاريانت</option>');

                if (productId) {
                    $.ajax({
                        url: "{{route('admin.orders.get-variants',':id')}}".replace(':id', productId),
                        method: 'GET',
                        success: function (response) {
                            if (response.variants.length > 0) {
                                variantSelect.prop('disabled', false);
                                response.variants.forEach(function (variant) {
                                    var optionValuesText = variant.option_values.map(function (optionValue) {
                                        return optionValue.value.ar;
                                    }).join(', ');

                                    variantSelect.append(
                                        `<option value="${variant.id}"
                                data-price-retail="${variant.price}"
                                data-price-wholesale="${variant.goomla_price}"
                                data-discount-type="${variant.discount_type}"
                                data-discount-value="${variant.discount_value}"
                                data-quantity="${variant.quantity}">
                                ${optionValuesText}
                            </option>`
                                    );
                                });

                                // تفريغ الحقول إذا كان المنتج يحتوي على فاريانتس
                                clearProductFields(productBody);
                            } else {
                                // إذا لم يكن هناك فاريانت، استخدم قيم المنتج
                                setProductFieldsFromProduct(productBody, selectedProduct);
                            }
                        }
                    });
                } else {
                    clearProductFields(productBody);
                }

                initializeSelect2();
            });

            $('#product-fields').on('change', '.product-variant', function () {
                var selectedVariant = $(this).find(':selected');
                var productBody = $(this).closest('.card-body');
                var selectedOption = $(this).find('option[value=""]');

                // تعطيل وتفعيل اول اوبشن في اختيار الفاريانتس (وهو اختر الفاريانت)
                if (this.value !== "") {
                    selectedOption.prop('disabled', true); // تعطيل خيار "اختر الفاريانت"
                } else {
                    selectedOption.prop('disabled', false); // تمكين الخيار إذا لم يتم اختيار أي شيء
                }

                if (selectedVariant.val()) {
                    var priceRetail = selectedVariant.data('price-retail');
                    var priceWholesale = selectedVariant.data('price-wholesale');
                    var discountType = selectedVariant.data('discount-type');
                    var discountValue = selectedVariant.data('discount-value');
                    var quantity = selectedVariant.data('quantity');
                    var userType = $('#inputUser').find(':selected').data('user-type'); // نوع العميل

                    var price = userType === 'goomla' ? priceWholesale : priceRetail;

                    updateProductPricing(productBody, price, discountType, discountValue, quantity);
                }
            });

            function updateProductPricing(productBody, price, discountType, discountValue, quantity) {
                var discountAmount = 0;

                if (discountType === 'percentage') {
                    discountAmount = (price * discountValue) / 100;
                } else if (discountType === 'fixed') {
                    discountAmount = discountValue;
                }

                var finalPrice = price - discountAmount;

                productBody.find('.product-current-quantity').val(quantity);
                productBody.find('.product-price').val(price);
                productBody.find('.product-discount').val(discountAmount);
                productBody.find('.product-discountPrice').val(finalPrice);

                updateProductTotal(productBody);
            }

            function clearProductFields(productBody) {
                productBody.find('.product-current-quantity').val('');
                productBody.find('.product-price').val('');
                productBody.find('.product-discount').val('');
                productBody.find('.product-discountPrice').val('');
                productBody.find('.product-total').val('');
            }

            function setProductFieldsFromProduct(productBody, selectedProduct) {
                var priceRetail = selectedProduct.data('price-retail');
                var priceWholesale = selectedProduct.data('price-wholesale');
                var discountType = selectedProduct.data('discount-type');
                var discountValue = selectedProduct.data('discount-value');
                var quantity = selectedProduct.data('quantity');
                var userType = $('#inputUser').find(':selected').data('user-type'); // نوع العميل

                var price = userType === 'goomla' ? priceWholesale : priceRetail;

                updateProductPricing(productBody, price, discountType, discountValue, quantity);
            }

            $('#product-fields').on('input', '.product-quantity', function () {
                $('#promo_code').removeAttr('readonly');
                $('#applyCouponButton').removeAttr('disabled');
                $('#copounDiscountAmount').val('0');


                updateProductTotal($(this).closest('.card-body'));

                var $productField = $(this).closest('.product-field');

                getFreeQuantity($productField, function (freeQuantity) {
                    // هنا تستطيع استخدام الكمية المجانية كما تريد
                    $productField.find('.free-quantity').html(
                        freeQuantity > 0 ? `+ عدد <span class="free-quantity-number">${freeQuantity}</span> قطعة مجاني` : ''
                    );
                });


            });

            function updateProductTotal(productBody) {
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


            // تحديث الخصم عند تغيير المستخدم
            $('#inputUser').change(function () {

                // الحصول على نسبة الخصم من الـ data attribute
                vipDiscountRate = parseFloat($(this).find(':selected').data('vip-discount')) || 0;
                $('#inputDiscountPercentage').val(vipDiscountRate.toFixed(2) + '%');
                $('#copounDiscountAmount').val('0');


                updateUserTypeAndPrices();
                // updateDiscount();
                updateTotalOrder(); // إعادة حساب إجمالي الطلب بعد التحديث

                // الحصول على الـ user_id المختار
                const userId = $(this).val();
                // أظهار كود كوبون الخصم للاعضاء فقط
                if (userId) {
                    $('#coupon-group-id').show();
                    $('#promo_code').removeAttr('readonly');
                    $('#applyCouponButton').removeAttr('disabled');
                } else {
                    $('#coupon-group-id').hide();
                }

                // إرسال طلب AJAX لجلب العناوين الخاصة بالمستخدم
                if (userId) {
                    $.ajax({
                        url: "{{route('admin.get-customer-address',':userId')}}".replace(':userId', userId), // رابط الـ API لجلب العناوين
                        method: 'GET',
                        success: function (response) {
                            // تأكد من أن الاستجابة تحتوي على بيانات العنوان
                            if (response.success) {
                                const address = response.address;

                                // تعبئة الحقول الخاصة بالعناوين
                                $('#inputAddress').val(address.address);
                                $('#inputPhone').val(address.phone);
                                $('#inputCity').val(address.city);
                                $('#state').val(address.state);
                                $('#full_name').val(address.full_name);
                            } else {
                                // في حالة عدم وجود عنوان، افراغ الحقول
                                $('#inputAddress').val('');
                                $('#inputPhone').val('');
                                $('#inputCity').val('');
                                $('#state').val('');
                                $('#full_name').val('');
                            }
                        },
                        error: function () {
                            alert('حدث خطأ أثناء جلب العنوان');
                            // تفريغ الحقول عند حدوث خطأ
                            $('#inputAddress').val('');
                            $('#inputPhone').val('');
                            $('#inputCity').val('');
                            $('#state').val('');
                            $('#full_name').val('');
                        }
                    });
                } else {
                    // تفريغ الحقول إذا لم يتم اختيار مستخدم
                    $('#inputAddress').val('');
                    $('#inputPhone').val('');
                    $('#inputCity').val('');
                    $('#state').val('');
                    $('#full_name').val('');
                }

            });

            function updateUserTypeAndPrices() {
                var userType = $('#inputUser').find(':selected').data('user-type'); // جلب نوع العميل

                $('.product-select').each(function () {
                    var selectedProduct = $(this).find(':selected');
                    var productBody = $(this).closest('.card-body');

                    var priceRetail, priceWholesale, discountType, discountValue, quantity;

                    if (selectedProduct.data('has-variants') === true) {
                        var variantSelect = productBody.find('.product-variant');
                        var selectedVariant = variantSelect.find(':selected');

                        if (selectedVariant.val() !== '') {
                            priceRetail = selectedVariant.data('price-retail');
                            priceWholesale = selectedVariant.data('price-wholesale');
                            discountType = selectedVariant.data('discount-type');
                            discountValue = selectedVariant.data('discount-value');
                            quantity = selectedVariant.data('quantity');
                        } else {
                            // وضع القيم الافتراضية في حالة عدم اختيار الفاريانت
                            priceRetail = '';
                            priceWholesale = '';
                            discountType = '';
                            discountValue = '';
                            quantity = '';
                        }
                    } else {
                        priceRetail = selectedProduct.data('price-retail');
                        priceWholesale = selectedProduct.data('price-wholesale');
                        discountType = selectedProduct.data('discount-type');
                        discountValue = selectedProduct.data('discount-value');
                        quantity = selectedProduct.data('quantity');
                    }

                    // تحديد السعر بناءً على نوع المستخدم
                    var price = userType === 'goomla' ? priceWholesale : priceRetail;

                    // تحديث الأسعار والخصومات في المنتج
                    updateProductPricing(productBody, price, discountType, discountValue, quantity);

                    // تحديث الكمية المجانية
                    getFreeQuantity(productBody, function (freeQuantity) {
                        productBody.find('.free-quantity').html(
                            freeQuantity > 0 ? `+ عدد <span class="free-quantity-number">${freeQuantity}</span> قطعة مجاني` : ''
                        );
                    });
                });

                // إعادة تهيئة Select2 بعد التحديث
                initializeSelect2();
            }


            // تعديل دالة updateTotalOrder
            function updateTotalOrder() {
                var totalOrder = 0;
                $('.product-total').each(function () {
                    totalOrder += parseFloat($(this).val()) || 0;
                });
                var copounDisount = $('#copounDiscountAmount').val();
                var discountAmount = (totalOrder * vipDiscountRate) / 100;
                $('#totalBeforeDiscount').val(totalOrder.toFixed(2));
                $('#inputDiscountPercentage').val(vipDiscountRate.toFixed(2) + '%');
                $('#inputDiscountAmount').val(discountAmount.toFixed(2));
                $('#inputTotalOrder').val((totalOrder - discountAmount - copounDisount).toFixed(2));
                //updateDiscount();  // نضيف هذا السطر لتحديث الخصم بعد تحديث الإجمالي
            }

            $('#orderForm').on('submit', function (e) {
                e.preventDefault();

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

                    let formData = new FormData(this);  // جمع البيانات من الفورم

                    $.ajax({
                        type: 'POST',
                        url: '{{ route('admin.orders.store') }}',  // نفس المسار القديم لتخزين البيانات
                        data: formData,
                        processData: false,
                        contentType: false,
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

            $('#applyCouponButton').click(function () {
                var couponCode = $('#promo_code').val(); // الكود المُدخل للكوبون
                var totalOrder = $('#totalBeforeDiscount').val(); // إجمالي الطلب
                var userId = $('#inputUser').val();
                $.ajax({
                    url: "{{ route('admin.check-promo-code') }}",
                    method: 'POST',
                    data: {
                        promo_code: couponCode,
                        total_order: totalOrder,
                        user_id: userId,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function (response) {
                        // تحقق من وجود خصم في الرد
                        if (response.discount !== undefined) {
                            $('#copounDiscountAmount').val(response.discount);
                            $('#promo_code').attr('readonly', true);
                            $('#applyCouponButton').attr('disabled', 'disabled');
                            updateTotalOrder();
                            toastr.success(response.success);
                        } else {
                            toastr.error('حدث خطأ غير متوقع. حاول مرة أخرى.');
                        }
                    },
                    error: function (xhr) {
                        let error = JSON.parse(xhr.responseText);
                        if (error.error) {
                            toastr.error(error.error);
                        } else {
                            toastr.error('حدث خطأ غير متوقع. حاول مرة أخرى.');
                        }
                        $('#promo_code').val('');
                    }
                });
            });


            // -------------------  حساب تكلفة الشحن --------------------

            const stateSelect = $('select[name="state"]');


            // وظيفة لحساب تكلفة الشحن
            function calculateShippingCost(state) {
                if (!state) {
                    $('#shipping_cost').val('غير متوفر');
                    return;
                }

                $.ajax({
                    url: "{{route('admin.checkout.getShippingCost',':state')}}".replace(':state', state),
                    method: 'GET',
                    success: function (response) {
                        const shippingCost = response.shipping_cost;
                        if (shippingCost == 0 || !shippingCost) {
                            $('#shipping_cost').val(' شحن مجاني');
                            alert('0000')
                        } else {
                            $('#shipping_cost').val(shippingCost + ' جنيه  ');
                        }
                    },

                    error: function () {
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
            stateSelect.change(function () {
                const state = $(this).val();
                calculateShippingCost(state);


            });


            // الحصول على الكمية المجانية من المنتج اذا كان عليه عرض واستخدام الكولباك
            function getFreeQuantity($productField, callback) {

                var userType = $('#inputUser').find(':selected').data('user-type'); // الحصول على نوع العميل من البيانات المخزنة
                var productId = $productField.find('.product-select').val(); // ID المنتج
                var quantity = $productField.find('.product-quantity').val(); // الكمية المدخلة

                if (productId && quantity) {
                    $.ajax({
                        url: "{{route('admin.orders.free-quantity')}}",
                        method: 'GET',
                        data: {
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

        // اخفاء واظهار سيكشن العنوان واضافة المنتجات والتبديل بينهما
        document.addEventListener('DOMContentLoaded', function () {
            const initialFormSection = document.getElementById('initialFormSection');
            const addressSection = document.getElementById('addressSection');
            const showAddressButton = document.getElementById('showAddressButton');
            const submitOrderButton = document.getElementById('submitOrderButton');
            const goBackButton = document.getElementById('goBackButton');
            const ShippingDiv = document.getElementById('shipping-cost-div')

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
        label {
            font-size: 13px;
        }

        .coupon-input-group {
            display: flex;
            align-items: stretch;
        }

        .coupon-input-group .input-group-append {
            margin-right: 0.5rem;
        }

        .coupon-input-group .form-control,
        .coupon-input-group .input-group-append .btn {
            border-radius: 0.25rem;
        }

        @media (max-width: 575.98px) {
            .coupon-input-group {
                flex-direction: column;
            }

            .coupon-input-group > .form-control {
                width: 100%;
                margin-bottom: 0.5rem;
            }

            .coupon-input-group .input-group-append {
                width: 100%;
                margin-right: 0;
            }

            .coupon-input-group .input-group-append .btn {
                width: 100%;
            }
        }


    </style>
@endpush
