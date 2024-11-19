@forelse($products as $product)
    @if($product?->variants->isNotEmpty())
        {{-- Main product row for variant products --}}
        <tr>
            <td><input type="checkbox" class="product-checkbox" value="{{ $product?->id }}"></td>
            <td>{{ $loop->iteration }}</td>
            <td>
                {{ $product?->name }} - <small class="text-primary">متنوع</small>
                <br>
                <small class="text-muted">كود المنتج: {{ $product?->sku_code }}</small>
            </td>
            <td>
                @if($product?->images->first())
                    <img src="{{ asset('storage/' . $product?->images->first()->path) }}"
                         alt="{{ $product?->name }}"
                         class="img-fluid"
                         style="width: 50px; height: 50px; object-fit: cover;">
                @else
                    <span>لا توجد صورة</span>
                @endif
            </td>
            <td class="text-primary">منتج متنوع</td>
            <td class="text-primary">متنوع</td>
            <td>{{ $product?->discount ? $product?->discount->discount . ($product?->discount->discount_type == 'percentage' ? '%' : ' ج') : 'لا يوجد' }}</td>
            <td>{{ $product?->discount?->start_date ? $product?->discount->start_date->format('Y-m-d') : '---' }}</td>
            <td>{{ $product?->discount?->end_date ? $product?->discount->end_date->format('Y-m-d') : '---' }}</td>
            <td class="align-middle">
                <a href="{{ route('admin.products.show', $product?->id) }}" class="btn btn-sm btn-warning mb-1">
                    <i class="fas fa-eye"></i>
                </a>
                <a href="{{ route('admin.products.edit', $product?->id) }}" class="btn btn-sm btn-info mb-1">
                    <i class="fas fa-edit"></i>
                </a>
                @if(auth('admin')->user()->hasAnyRole(['superAdmin','supervisor']))
                    <button type="button" class="btn btn-sm btn-danger delete-product-btn mb-1" data-id="{{ $product->id }}">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                @endif
            </td>
        </tr>

        {{-- Variant rows --}}
        @foreach($product?->variants as $variant)
            <tr>
                <td><input type="checkbox" class="product-checkbox" value="{{ $product?->id }}"></td>
                <td>{{ $loop->parent->iteration }}.{{ $loop->iteration }}</td>
                <td>
                    <small class="text-muted">
                        كود المنتج: {{ $variant->sku_code }}
                        <br>
                        @foreach($variant->optionValues as $optionValue)
                            {{ $optionValue->option->name }}: {{ $optionValue->value }}
                            @if(!$loop->last) - @endif
                        @endforeach
                    </small>
                </td>
                <td>
                    @if($variant->images->first())
                        <img src="{{ asset('storage/' . $variant->images->first()->path) }}"
                             alt="{{ $product?->name }}"
                             class="img-fluid"
                             style="width: 50px; height: 50px; object-fit: cover;">
                    @else
                        <span>لا توجد صورة</span>
                    @endif
                </td>
                <td>{{ $variant->discounted_price }} ج</td>
                <td>{{ $variant->quantity }}</td>
                <td>{{ $product?->discount ? $product?->discount->discount . ($product?->discount->discount_type == 'percentage' ? '%' : ' ج') : 'لا يوجد' }}</td>
                <td>{{ $product?->discount?->start_date ? $product?->discount->start_date->format('Y-m-d') : '---' }}</td>
                <td>{{ $product?->discount?->end_date ? $product?->discount->end_date->format('Y-m-d') : '---' }}</td>
                <td class="align-middle">
                    <a href="{{ route('admin.products.show', $product->id) }}" class="btn btn-sm btn-warning mb-1">
                        <i class="fas fa-eye"></i>
                    </a>
                    <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-sm btn-info mb-1">
                        <i class="fas fa-edit"></i>
                    </a>
                    @if(auth('admin')->user()->hasAnyRole(['superAdmin','supervisor']))
                        <button type="button" class="btn btn-sm btn-danger delete-product-btn mb-1" data-id="{{ $product->id }}">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    @endif
                </td>
            </tr>
        @endforeach
    @else
        {{-- Products without variants --}}
        <tr>
            <td><input type="checkbox" class="product-checkbox" value="{{ $product?->id }}"></td>
            <td>{{ $loop->iteration }}</td>
            <td>
                {{ $product?->name }}
                <br>
                <small class="text-muted">كود المنتج: {{ $product?->sku_code }}</small>
            </td>
            <td>
                @if($product?->images->first())
                    <img src="{{ asset('storage/' . $product?->images->first()->path) }}"
                         alt="{{ $product?->name }}"
                         class="img-fluid"
                         style="width: 50px; height: 50px; object-fit: cover;">
                @else
                    <span>لا توجد صورة</span>
                @endif
            </td>
            <td>{{ $product?->discounted_price }} ج</td>
            <td>{{ $product?->available_quantity }}</td>
            <td>{{ $product?->discount ? $product?->discount->discount . ($product?->discount->discount_type == 'percentage' ? '%' : ' ج') : 'لا يوجد' }}</td>
            <td>{{ $product?->discount?->start_date ? $product?->discount->start_date->format('Y-m-d') : '---' }}</td>
            <td>{{ $product?->discount?->end_date ? $product?->discount->end_date->format('Y-m-d') : '---' }}</td>
            <td class="align-middle">
                <div class="action-icons">
                    <!-- زر عرض التفاصيل -->
                    <a href="{{ route('admin.products.show', $product->id) }}" class="btn btn-warning" title="عرض التفاصيل">
                        <i class="fas fa-eye"></i>
                    </a>

                    <!-- زر التعديل -->
                    <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-info" title="تعديل المنتج">
                        <i class="fas fa-edit"></i>
                    </a>

                    <!-- زر الحذف -->
                    @if(auth('admin')->user()->hasAnyRole(['superAdmin', 'supervisor']))
                        <button type="button" class="btn btn-danger delete-product-btn" data-id="{{ $product->id }}" title="حذف المنتج">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    @endif
                </div>
            </td>


        </tr>
    @endif
@empty
    <tr>
        <td colspan="10">لا يوجد منتجات حاليا</td>
    </tr>
@endforelse
