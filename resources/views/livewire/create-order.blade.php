<div>
    <div class="card card-info">
        <div class="card-header">
            <h3 class="card-title float-left">إضافة طلب جديد</h3>
        </div>
        <form wire:submit.prevent="saveOrder" class="form-horizontal" dir="rtl">
            <div class="card-body">
                <div class="form-group row">
                    <label for="inputUser" class="col-sm-2 control-label">المستخدم</label>
                    <div class="col-sm-10">
                        <select wire:model="user_id" class="custom-select form-control @error('user_id') is-invalid @enderror" id="inputUser" required>
                            <option value="" >اختر مستخدم</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                        @error('user_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="form-group row">
                    <label for="inputProduct" class="col-sm-2 control-label">المنتجات</label>
                    <div class="col-sm-10">
                        <div id="product-fields">
                            @foreach($selectedProducts as $index => $selectedProduct)
                                <div class="product-field card mb-3">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <h5 class="card-title mb-0 product-number">المنتج {{ $index + 1 }}</h5>
                                            <button type="button" wire:click="removeProduct({{ $index }})" class="btn btn-danger btn-sm">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                        <div class="form-group">
                                            <select wire:model.live="selectedProducts.{{ $index }}.product_id" 
                                                    wire:change="updateProduct({{ $index }}, 'product_id')"
                                                    class="form-control product-select custom-select" required>
                                                <option value="" disabled="disabled" style="line-height: 20px;">اختر منتج</option>
                                                @foreach($products as $product)
                                                    <option value="{{ $product->id }}">{{ $product->name }}</option>
                                                @endforeach
                                            </select>
                                            @error('selectedProducts.'.$index.'.product_id') <span class="text-danger">{{ $message }}</span> @enderror
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="product-quantity-{{ $index }}">الكمية</label>
                                                    <input type="number" wire:live.lazy="selectedProducts.{{ $index }}.quantity" 
                                                           wire:change="updateProductTotal({{ $index }})"
                                                           class="form-control product-quantity" required>
                                                    @error('selectedProducts.'.$index.'.quantity') <span class="text-danger">{{ $message }}</span> @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="product-price-{{ $index }}">السعر</label>
                                                    <input type="text" wire:model.live="selectedProducts.{{ $index }}.price" class="form-control product-price" readonly>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="product-total-{{ $index }}">الإجمالي</label>
                                                    <input type="text" wire:model="selectedProducts.{{ $index }}.total" class="form-control product-total" readonly>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="text-left">
                            <button type="button" wire:click="addProduct" class="btn btn-secondary mt-2">إضافة منتج آخر</button>
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="inputTotalOrder" class="col-sm-2 control-label">الإجمالي الكلي للطلب</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="inputTotalOrder" wire:model="totalOrder" readonly>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-info float-right">حفظ البيانات</button>
            </div>
        </form>
    </div>
</div>
@push('styles')
<style>
    .custom-select {
    padding-right: 30px !important; 
    background-position: left 0.75rem center !important; /* Adjust arrow position for RTL */
    background-size: 16px 12px !important; /* Ensure arrow size is appropriate */
}
</style>
@endpush