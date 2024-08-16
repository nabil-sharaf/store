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
        <form class="form-horizontal" action="{{ route('admin.orders.update', $order->id) }}" method="POST" enctype="multipart/form-data" dir="rtl">
            @csrf
            @method('PUT')
            <div class="card-body">
                <!-- User selection -->
                <div class="form-group row">
                    <label for="inputUser" class="col-sm-2 control-label">المستخدم</label>
                    <div class="col-sm-10">
                        <select class="custom-select form-control @error('user_id') is-invalid @enderror" id="inputUser" name="user_id" required>
                            <option value="">اختر مستخدم</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ old('user_id', $order->user_id) == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }}
                                </option>
                            @endforeach
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
                                @include('admin.orders.partials.product-field')
                            @endforeach
                        </div>
                        <div class="text-left">
                            <button type="button" class="btn btn-secondary mt-2" id="addProductButton">إضافة منتج آخر</button>
                        </div>
                    </div>
                </div>

                <!-- Total Order -->
                <div class="form-group row">
                    <label for="inputTotalOrder" class="col-sm-2 control-label">الإجمالي الكلي للطلب</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="inputTotalOrder" name="total_price" value="{{ old('total_price', $order->total_price) }}" readonly>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-info float-right">تحديث البيانات</button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function () {
            var productCount = {{ $order->orderDetails->count() - 1 }};

            $('#addProductButton').click(function () {
                addProductField();
            });

            function addProductField() {
                productCount++;
                $.get('{{ route("admin.orders.product-field") }}', { index: productCount }, function(data) {
                    $('#product-fields').append(data);
                    updateProductNumbers();
                    updateTotalOrder();
                });
            }

            function updateProductNumbers() {
                $('.product-field').each(function (index) {
                    $(this).find('.product-number').text('المنتج ' + (index + 1));
                });
            }

            $('#product-fields').on('click', '.remove-product', function () {
                $(this).closest('.product-field').remove();
                updateProductNumbers();
                updateTotalOrder();
            });

            $('#product-fields').on('change', '.product-select', function () {
                var price = $(this).find(':selected').data('price');
                var priceField = $(this).closest('.card-body').find('.product-price');
                priceField.val(price);
                updateTotal($(this).closest('.card-body'));
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

            function updateTotalOrder() {
                var totalOrder = 0;
                $('.product-total').each(function () {
                    totalOrder += parseFloat($(this).val()) || 0;
                });
                $('#inputTotalOrder').val(totalOrder.toFixed(2));
            }

            // Initialize totals on page load
            $('.product-field').each(function() {
                updateTotal($(this));
            });
            updateTotalOrder();
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
