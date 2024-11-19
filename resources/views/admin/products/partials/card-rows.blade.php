@forelse($products as $product)
    @php
        $mainImage = $product->images->first();
        $hasVariants = $product->variants->isNotEmpty();
    @endphp

    {{-- Main Product Card --}}
    <div class="col-12 mb-3">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div class="form-check">
                    <input type="checkbox" class="form-check-input product-checkbox" value="{{ $product?->id }}">
                </div>
                <span class="badge {{ $hasVariants ? 'bg-info' : 'bg-primary' }}">
                    {{ $hasVariants ? 'متنوع' : 'منتج' }}
                </span>
            </div>
            <div class="card-body">
                <div class="d-flex">
                    <div class="flex-shrink-0 ml-3">
                        @if($mainImage)
                            <img src="{{ asset('storage/' . $mainImage->path) }}"
                                 alt="{{ $product->name }}"
                                 class="img-thumbnail"
                                 style="width: 100px; height: 100px; object-fit: cover;">
                        @else
                            <div class="bg-light p-3 text-center">
                                <i class="fas fa-image text-muted"></i>
                            </div>
                        @endif
                    </div>
                    <div class="flex-grow-1">
                        <h5 class="card-title mb-1">{{ $product->name }}</h5>
                        <p class="text-muted small mb-2">كود المنتج: {{ $product->sku_code }}</p>

                        <div class="d-flex justify-content-between">
                            <div>
                                <span class="badge bg-primary">
                                    {{ $hasVariants ? 'متنوع' : $product->discounted_price . ' ج' }}
                                </span>
                                <span class="badge bg-secondary mr-1">
                                    الكمية: {{ $hasVariants ? 'متنوع' : $product->available_quantity }}
                                </span>
                            </div>
                            <div class="actions">
                                <div class="actions d-flex flex-column align-items-center">
                                    <a href="{{ route('admin.products.show', $product->id) }}" class="btn btn-sm btn-outline-warning mb-2">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-sm btn-outline-info mb-2">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @if(auth('admin')->user()->hasAnyRole(['superAdmin','supervisor']))
                                        <button type="button" class="btn btn-sm btn-outline-danger delete-product-btn mb-2" data-id="{{ $product->id }}">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>

                        @if($product->discount)
                            <div class="mt-2">
                                <span class="badge bg-warning">
                                    خصم: {{ $product->discount->discount }}{{ $product->discount->discount_type == 'percentage' ? '%' : ' ج' }}
                                </span>
                                <small class="text-muted d-block">
                                    من {{ $product->discount->start_date?->format('Y-m-d') }} إلى {{ $product->discount->end_date?->format('Y-m-d') }}
                                </small>
                            </div>
                        @endif

                        @if($hasVariants)
                            <div class="mt-2">
                                <button class="btn btn-sm btn-outline-secondary" type="button" data-toggle="collapse"
                                        data-target="#variants-{{ $product->id }}"
                                        aria-expanded="false"
                                        aria-controls="variants-{{ $product->id }}">
                                    عرض التنوعات ({{ $product->variants->count() }})
                                </button>
                                <div class="collapse mt-2" id="variants-{{ $product->id }}">
                                    @foreach($product->variants as $variant)
                                        <div class="card card-body mb-2">
                                            <div class="d-flex justify-content-between">
                                                <div>
                                                    <small class="text-muted d-block">
                                                        @foreach($variant->optionValues as $optionValue)
                                                            {{ $optionValue->option->name }}: {{ $optionValue->value }}
                                                            @if(!$loop->last) - @endif
                                                        @endforeach
                                                    </small>
                                                    <span class="badge bg-primary">
                                                        {{ $variant->discounted_price }} ج
                                                    </span>
                                                    <span class="badge bg-secondary ml-1">
                                                        الكمية: {{ $variant->quantity }}
                                                    </span>
                                                    <small class="text-muted d-block mt-1">
                                                        الكود:<br>
                                                        {{ $variant->sku_code }}
                                                    </small>

                                                </div>
                                                @if($variant->images->first())
                                                    <img src="{{ asset('storage/' . $variant->images->first()->path) }}"
                                                         alt="{{ $variant->sku_code }}"
                                                         class="img-thumbnail"
                                                         style="width: 50px; height: 50px; object-fit: cover;">
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@empty
    <div class="col-12">
        <div class="alert alert-info text-center">
            لا يوجد منتجات حاليا
        </div>
    </div>
@endforelse
