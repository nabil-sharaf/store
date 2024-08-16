<div class="product-field card mb-3">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <h5 class="card-title mb-0 product-number">المنتج {{ $index + 1 }}</h5>
            <button type="button" class="btn btn-danger btn-sm remove-product">
                <i class="fas fa-trash"></i>
            </button>
        </div>
        <div class="form-group">
            <label for="product-select-{{ $index }}">اختر المنتج</label>
            <select id="product-select-{{ $index }}" class="custom-select form-control product-select"
                    name="products[{{ $index }}][id]" required>
                <option value="">اختر منتج</option>
                @foreach($products as $product)
                    <option value="{{ $product->id }}" data-price="{{ $product->price }}"
                        {{ isset($orderDetail) && $orderDetail->product_id == $product->id ? 'selected' : '' }}>
                        {{ $product->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">

                    <label for="product-quantity-{{ $index }}">الكمية</label>
                    <input type="number" id="product-quantity-{{ $index }}"
                           name="products[{{ $index }}][quantity]"
                           class="form-control product-quantity"
                           value="{{ isset($orderDetail) ? $orderDetail->product_quantity : old('products.'.$index.'.quantity', 1) }}"
                           required min="1">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="product-price-{{ $index }}">السعر</label>
                    <input type="text" id="product-price-{{ $index }}"
                           class="form-control product-price"
                           value="{{ isset($orderDetail) ? $orderDetail->price : '' }}" readonly>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="product-total-{{ $index }}">الإجمالي</label>
                    <input type="text" id="product-total-{{ $index }}"
                           class="form-control product-total"
                           value="{{ isset($orderDetail) ? $orderDetail->price * $orderDetail->Product_quantity : '' }}" readonly>
                </div>
            </div>
        </div>
    </div>
</div>
