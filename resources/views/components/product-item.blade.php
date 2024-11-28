<div class="product-item">
    <div class="product-thumb">
        @php
            $firstVariant = $product->variants->first();
            $imagePath = $firstVariant && $firstVariant->images->first()
                ? $firstVariant->images->first()->path
                : ($product->images->first()?->path ?: $siteImages?->default_image);

            $price = $firstVariant
                ? $firstVariant->variant_price
                : $product->product_price;
            $discountedPrice = $firstVariant
                ? $firstVariant->discounted_price
                : $product->discounted_price;
        @endphp
        <img src="{{ asset('storage/' . $imagePath) }}" alt="{{ $product->name }}">

        @if($product->customer_offer)
            <span class="badge badge-offer">{{ $product->customer_offer}}</span>
        @endif

        <div class="product-action">
            @if(! $product->hasVariants())
                <a class="action-quick-view-cart" href="#"
                   onclick="addToCart(event, {{ $product->id }},
                    {{ $product->variants->count() > 0 ? $product->variants->first()->id : 'null' }})">
                    <i class="ion-ios-cart"></i>
                </a>
            @endif

            <a class="action-quick-view" href="javascript:void(0)"
               onclick="showProductDetails({{$product->id}})">
                <i class="ion-arrow-expand"></i>
            </a>
            <a class="action-quick-view-wishlist" href="#"
               onclick="wishListAdd(event,this, {{$product->id}})">
                <i class="ion-heart"></i>
            </a>
        </div>
    </div>

    <div class="product-info">
        <div class="product-info-content">
            <div class="rating">
                <a href="{{ route('product.show', $product->id) }}">
                    @for($i = 1; $i <= 5; $i++)
                        <span class="fa fa-star {{ $i <= $product->rating ? 'active' : '' }}"></span>
                    @endfor
                </a>
            </div>

            <h4 class="title">
                <a href="{{ route('product.show', $product->id) }}">{{ $product->name }}</a>
            </h4>

            @if($product->variants->count() > 0)
                <div class="color-options mb-2">
                    <div class="color-circles">
                        @php
                            $colorOptions = collect();
                            foreach($product->variants as $variant) {
                                foreach($variant->optionValues as $optionValue) {
                                    if($optionValue->option && strtolower($optionValue->option->getTranslation('name', 'en')) === 'color') {
                                        $colorValue = $optionValue->getTranslation('value', 'en');
                                        $colorCode = \App\Services\ColorService::resolveColorCode($colorValue);

                                        $colorOptions->push([
                                            'variant_id' => $variant->id,
                                            'color_value' => $colorCode,
                                            'color_name' => $optionValue->getTranslation('value', app()->getLocale()),
                                        ]);
                                    }
                                }
                            }
                            $uniqueColors = $colorOptions->unique('color_value');
                        @endphp

                        @foreach($uniqueColors as $colorOption)
                            <div class="color-circle-wrapper">
                                <button
                                    class="color-circle variant-color-btn {{ $loop->first ? 'active' : '' }}"
                                    style="background-color: {{ $colorOption['color_value'] }};"
                                    data-variant-id="{{ $colorOption['variant_id'] }}"
                                    title="{{ $colorOption['color_name'] }}">
                                </button>
                                <input type="hidden" class="selected-variant-id" data-product-id="{{ $product->id }}"
                                       value="">
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <div class="prices">
                <a href="{{ route('product.show', $product->id) }}">
                    <x-product-price
                        :productPrice="$price"
                        :discountedPrice="$discountedPrice"
                    />
                </a>
            </div>
        </div>
    </div>
</div>
@push('scripts')
    <script>
        // تعريف function خارج event handler لتجنب التكرار
        function updateProductVariant(element) {
            // التأكد من عدم وجود request جارٍ
            if ($(element).data('loading')) {
                return;
            }

            var variantId = $(element).data('variant-id');
            var url = "{{ route('home.variant.details', ':variantId') }}".replace(":variantId", variantId);
            var storageUrl = "{{ asset('storage') }}";

            // تعليم العنصر كـ loading
            $(element).data('loading', true);

            // إزالة active class من كل الأزرار في نفس المنتج فقط
            var $productItem = $(element).closest('.product-item');
            $productItem.find('.variant-color-btn').removeClass('active');
            $(element).addClass('active');

            $.ajax({
                url: url,
                method: 'GET',
                dataType: 'json',
                success: function (data) {
                    var $img = $productItem.find('.product-thumb img');
                    var $priceContainer = $productItem.find('.prices');
                    var $hiddenInput = $productItem.find('.selected-variant-id');
                    var $productId = $($hiddenInput).data('product-id');

                    if (data.image_path) {
                        $img.attr('src', storageUrl + '/' + data.image_path);
                    }

                    if (data.price_html) {
                        $priceContainer.html(data.price_html);
                    }

                    $($hiddenInput).val(variantId);

                    // تحديث الروابط
                    var $actionQuickViewCart = $productItem.find('.action-quick-view-cart');
                    var $actionQuickView = $productItem.find('.action-quick-view');
                    var $actionQuickViewWishlist = $productItem.find('.action-quick-view-wishlist');

                    $actionQuickViewCart.attr('onclick', `addToCart(event, ${$productId})`);
                    $actionQuickView.attr('data-id', variantId);
                    $actionQuickViewWishlist.attr('data-id', variantId);
                },
                error: function (xhr, status, error) {
                    console.error('Error:', error);
                },
                complete: function () {
                    // إزالة علامة loading بعد اكتمال الطلب
                    $(element).data('loading', false);
                }
            });
        }

        $(document).ready(function () {
            // استخدام event delegation بدلاً من تعيين handler لكل زر
            $(document).on('click', '.variant-color-btn:not(.active)', function (e) {
                e.preventDefault();
                updateProductVariant(this);
            });

            // إزالة event handler القديم
            $('.variant-color-btn').off('click');
        });
    </script>
@endpush

@push('styles')
    <style>
        .product-item {
            position: relative;
        }

        .color-options {
            margin: 0; /* تقليل الهوامش */
            position: absolute;
            bottom: 25%;
            left: 0;
            right: 0;
            padding: 5px;
            z-index: 1;
            background: none; /* إلغاء الخلفية */
        }


        .color-circles {
            display: flex;
            gap: 5px;
            flex-wrap: wrap;
            justify-content: center;
        }

        .color-circle-wrapper {
            position: relative;
            padding: 2px;
        }

        .color-circle {
            width: 20px; /* تقليل حجم الدوائر قليلاً */
            height: 20px;
            border-radius: 50%;
            border: 1px solid #ddd;
            cursor: pointer;
            padding: 0;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .color-circle:hover {
            transform: scale(1.1);
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.2);
        }

        .color-circle.active {
            border: 2px solid whitesmoke;
            background: rgba(0, 0, 0, 0.1); /* يمكنك وضع خلفية خفيفة أو لون خلفية */
            position: relative;
        }

        /* تعديل لون علامة الاختيار حسب خلفية اللون */
        .color-circle.active::after {
            content: '✓';
            position: absolute;
            top: 45%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 11px;
            color: white; /* الافتراضي أبيض */
            text-shadow: 0 0 2px rgba(0, 0, 0, 0.5); /* تحسين الوضوح */
            mix-blend-mode: difference; /* تبديل اللون تلقائيًا */
        }

        .product-item {
            border-radius: 8px;
        }

        .product-item .product-info {
            text-align: center;
            height: 117px;
        }

        .product-item .product-info .title {
            line-height: 1.25;
        }


        @media (max-width: 767px) {
            .product-item .product-info {
                padding-block: 22px 4px !important;
                padding-inline: 18px !important;
            }

            .product-item .product-info .rating {
                margin-bottom: 10px !important;
                line-height: .8;
            }

            .color-options {
                bottom: 23%;
            }

            .product-item .product-thumb {
                min-height: 195px;
            }
        }

    </style>
@endpush

