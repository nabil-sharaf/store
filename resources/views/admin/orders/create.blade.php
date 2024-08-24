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
                                <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }}
                                </option>
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
                                                <option value="{{ $product->id }}"
                                                        data-price="{{ $product->price }}"
                                                        data-quantity="{{ $product->quantity }}">{{ $product->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="product-quantity-0">الكمية المطلوبة</label>
                                                <input type="number" id="product-quantity-0"
                                                       name="products[0][quantity]"
                                                       class="form-control product-quantity" required min="1">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="product-current-quantity-0">الكمية المتاحة للمنتج</label>
                                                <input type="text" id="product-current-quantity-0"
                                                       class="form-control product-current-quantity" readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="product-price-0">السعر</label>
                                                <input type="text" id="product-price-0"
                                                       class="form-control product-price" readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
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
                    <label for="inputTotalOrder" class="col-sm-2 control-label">الإجمالي  بعد الخصم</label>
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
                <option value="{{ $product->id }}" data-price="{{ $product->price }}" data-quantity="{{ $product->quantity }}">{{ $product->name }}</option>
                            @endforeach
                </select>
            </div>
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="product-quantity-${productCount}">الكمية المطلوبة</label>
                        <input type="number" id="product-quantity-${productCount}" name="products[${productCount}][quantity]" class="form-control product-quantity" required value='1' min='1'>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="product-current-quantity-${productCount}">الكمية المتاحة للمنتج</label>
                        <input type="text" id="product-current-quantity-${productCount}" class="form-control product-current-quantity" readonly>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="product-price-${productCount}">السعر</label>
                        <input type="text" id="product-price-${productCount}" class="form-control product-price" readonly>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="product-total-${productCount}">الإجمالي</label>
                        <input type="text" id="product-total-${productCount}" class="form-control product-total" readonly>
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
                var price = selectedProduct.data('price');
                var quantity = selectedProduct.data('quantity');
                var productBody = $(this).closest('.card-body');

                productBody.find('.product-price').val(price);
                productBody.find('.product-current-quantity').val(quantity);

                updateTotal(productBody);
                initializeSelect2();
            });

            $('#product-fields').on('input', '.product-quantity', function () {
                updateTotal($(this).closest('.card-body'));
            });

            function updateTotal(productBody) {
                var price = parseFloat(productBody.find('.product-price').val()) || 0;
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
                        noResults: function() {
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
                        success: function(response) {
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
                        error: function(xhr, status, error) {
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
            $('#inputUser').change(function() {
                updateDiscount();
            });

            // تعديل دالة updateTotalOrder
            function updateTotalOrder() {
                var totalOrder = 0;
                $('.product-total').each(function () {
                    totalOrder += parseFloat($(this).val()) || 0;
                });
                $('#inputTotalOrder').val(totalOrder.toFixed(2));
                updateDiscount();  // نضيف هذا السطر لتحديث الخصم بعد تحديث الإجمالي
            }




        });
    </script>
@endpush
