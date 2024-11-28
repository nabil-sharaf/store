@extends('front.layouts.app')
@section('title','تفاصيل المنج')
@section('content')

    <!--== Start Page Title Area ==-->
    <section class="page-title-area">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-12 m-auto">
                    <div class="page-title-content text-center">
                        <h2 class="title">تفاصيل المنتج</h2>
                        <div class="bread-crumbs"><a href="{{route('home.index')}}"> {{__('home.title')}} </a><span
                                class="breadcrumb-sep"> // </span><span class="active">اسم المنتج</span></div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--== End Page Title Area ==-->

    @php
        // تحديد أول لون وvariant افتراضي
        $variants = $product->variants->map(function($variant) {
            $variant->discounted_price = $variant->discounted_price;
            return $variant;
        });

        $optionValues = $variants->flatMap(function($variant) {
            return $variant->optionValues;
        });

        $options = $optionValues->map(function($value) {
            return $value->option ?? null;
        })->filter()->unique();

        $optionValuesGrouped = $optionValues->groupBy(function($value) {
            return $value->option_id;
        });

        // الحصول على أول لون افتراضياً
        $firstColorOption = $options->firstWhere('name', 'color');
        $firstColorValue = $firstColorOption ? $optionValuesGrouped[$firstColorOption->id]->first() : null;

        // الحصول على أول variant مرتبط بأول لون
        $defaultVariant = $firstColorValue
            ? $variants->first(function($variant) use ($firstColorValue) {
                return $variant->optionValues->contains('id', $firstColorValue->id);
            })
            : $variants->first();

        // تحضير الصور للعرض
        $defaultImages = $defaultVariant ? $defaultVariant->images : $product->images;
    @endphp


        <!--== Start Shop Area ==-->
    <section class="product-single-area">
        <div class="container">
            <div class="row">
                <!-- Product Images -->
                <div class="col-12 col-lg-6 mb-4 mb-lg-0">
                    <div class="single-product-slider">
                        <div class="swiper-container single-product-thumb-slider position-relative">
                            <div class="swiper-wrapper">
                                @forelse($defaultImages as $image)
                                    <div class="swiper-slide">
                                        <div class="thumb-item">
                                            <a href="{{asset('storage/'.$image->path)}}"
                                               data-fancybox="gallery"
                                               class="fancybox-item d-block">
                                                <img src="{{asset('storage/'.$image->path)}}"
                                                     alt="Product Image"
                                                     class="img-fluid product-image">
                                            </a>
                                        </div>
                                    </div>
                                @empty
                                    <div class="swiper-slide">
                                        <div class="thumb-item">
                                            <a href="{{ asset('storage/'.$siteImages->default_image) }}"
                                               data-fancybox="gallery"
                                               class="fancybox-item d-block">
                                                <img
                                                    src="{{ asset('storage/'.$siteImages->default_image) }}"
                                                    alt="Default Image"
                                                    class="img-fluid product-image">
                                            </a>
                                        </div>
                                    </div>
                                @endforelse
                            </div>

                            <div class="swiper-pagination position-relative mt-3"></div>
                            <div class="swiper-button-prev"></div>
                            <div class="swiper-button-next"></div>
                        </div>
                    </div>
                </div>
                <!-- Product Info -->
                <div class="col-12 col-lg-6">
                    <div class="single-product-info px-3">
                        <input type="hidden" id="selected_variant_id"
                               value="{{ $defaultVariant ? $defaultVariant->id : '' }}">
                        <h4 class="title mb-3">{{$product->name}}</h4>
                        <div class="prices mb-4">
                            <x-product-price
                                :productPrice="$defaultVariant ? $defaultVariant->price : $product->product_price"
                                :discountedPrice="$defaultVariant ? $defaultVariant->discounted_price : $product->discounted_price"/>
                        </div>
                        <!$-- Options -->
                        @forelse($options as $option)
                            <div class="variation-group mb-4">
                                <label class="mb-2 fw-bold"
                                       for="product-{{$option->id}}">{{$option->getTranslation('name',$locale)}}</label>

                                <!-- تعديل أزرار اختيار الألوان -->
                                @if(strtolower($option->getTranslation('name','en')) == 'color')
                                    <div class="color-thumbnails d-flex flex-wrap gap-3">
                                        @php $displayedColors = []; @endphp
                                        @foreach($optionValuesGrouped[$option->id] as $optionValue)
                                            @if(!in_array($optionValue->value, $displayedColors))
                                                <div class="color-thumbnail  text-center">
                                                    <input type="radio" class="product-input-thumbnail"
                                                           name="product-{{strtolower($option->getTranslation('name','en'))}}"
                                                           id="{{$option->getTranslation('name','en')}}-{{$optionValue->id}}"
                                                           value="{{$optionValue->id}}"
                                                           {{ $optionValue->id === $firstColorValue?->id ? 'checked' : '' }}
                                                           style="display: none;">
                                                    @php
                                                        $variant = $variants->first(function($variant) use ($optionValue) {
                                                            return $variant->optionValues->contains('id', $optionValue->id);
                                                        });
                                                        $variantImage = $variant->images->first();
                                                    @endphp
                                                    @if($variantImage)
                                                        <img src="{{ asset('storage/'.$variantImage->path) }}"
                                                             alt="{{$optionValue->value}}"
                                                             title="{{$optionValue->value}}"
                                                             class="img-fluid mb-2"
                                                             style="">
                                                    @endif
                                                    <span class="d-block text-muted small">
                                                    {{$optionValue->value}}
                                                </span>
                                                </div>
                                                @php $displayedColors[] = $optionValue->value; @endphp
                                            @endif
                                        @endforeach
                                    </div>
                                @else
                                    <select id="product-{{$option->id}}"
                                            name="product-{{strtolower($option->getTranslation('name','en'))}}"
                                            class="form-select mb-3">

                                        <option value="" selected>
                                            {{__('products.choose')}} {{$option->getTranslation('name',$locale)}}
                                        </option>
                                        @foreach($optionValuesGrouped[$option->id] as $optionValue)
                                            <option value="{{$optionValue->id}}">
                                                {{$optionValue->value}}
                                            </option>
                                        @endforeach
                                    </select>
                                @endif
                            </div>
                        @empty
                            <div class="product-rating">
                                <div class="rating">
                                    <span class="fa fa-star"></span>
                                    <span class="fa fa-star"></span>
                                    <span class="fa fa-star"></span>
                                    <span class="fa fa-star"></span>
                                    <span class="fa fa-star"></span>
                                </div>

                            </div>
                        @endforelse
                        @if($product->hasVariants())
                            <div class="variant-warning text-muted" style="font-size: 12px; margin-bottom: -10px; ">
                                <span class="text-danger font-bold">*</span> برجاء اختيار جميع خصائص المنتج من أجل الإضافة للسلة
                            </div>
                        @endif
                        <!-- Action Buttons -->
                        <div class="quick-product-action mt-4">
                            <div class="action-top">
                                <div class="row g-2">
                                    <div class="col-4 btn-product-qty">
                                        <div class="pro-qty m-0">
                                            <input type="number"
                                                   id="quantity_{{$product->id}}"
                                                   class="form-control text-center"
                                                   title="{{ __('aside_menu.quantity') }}"
                                                   value="1"
                                                   min="1"/>
                                        </div>
                                    </div>

                                    <div class="col-8 btn-product-cart">
                                        <div class="d-flex gap-2 ">
                                            <!-- تعديل زر Add to Cart -->
                                            <button class="btn btn-theme btn-add-cart flex-grow-1 text-nowrap px-3"
                                                    style="white-space: nowrap; min-width: fit-content;"
                                                    onclick="addToCart(event, {{$product->id}}, document.getElementById('quantity_{{$product->id}}').value, document.getElementById('selected_variant_id').value, {{json_encode($options)}})">
                                                {{ __('aside_menu.add_to_cart') }}
                                                &nbsp; <i class="fa fa-shopping-cart"></i>
                                            </button>

                                            <button class="btn btn-theme btn-wishlist p-0"
                                                    onclick="wishListAdd(event,this)"
                                                    data-id="{{ $product->id }}">
                                                <i class="fa fa-heart"></i>
                                                <i class="fa fa-heart"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Categories -->
                        <div class="widget mt-4">
                            <h3 class="title h5">{{__('aside_menu.categories')}}:</h3>
                            <div class="widget-tags">
                                @foreach($product->categories as $cat)
                                    <a href="{{route('category.show',$cat->id)}}" class="me-2">{{$cat->name}}</a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Product Description Tabs -->
                <div class="col-12 mt-5">
                    <div class="product-description-review">
                        <ul class="nav nav-tabs product-description-tab-menu mb-3" id="myTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="product-aditional-tab" data-bs-toggle="tab"
                                        data-bs-target="#commentProduct" type="button" role="tab"
                                        aria-selected="false">{{ __('aside_menu.information') }}</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="product-desc-tab" data-bs-toggle="tab"
                                        data-bs-target="#productDesc" type="button" role="tab"
                                        aria-controls="productDesc"
                                        aria-selected="true">{{ __('aside_menu.product_description') }}</button>
                            </li>
                        </ul>
                        <div class="tab-content product-description-tab-content" id="myTabContent">
                            <div class="tab-pane fade" id="commentProduct" role="tabpanel"
                                 aria-labelledby="product-aditional-tab">
                                <div class="product-desc">
                                    <p class="mb-0">{!! $product->info !!}</p>
                                </div>
                            </div>
                            <div class="tab-pane fade show active" id="productDesc" role="tabpanel"
                                 aria-labelledby="product-desc-tab">
                                <div class="product-desc">
                                    <p class="mb-0">{!! $product->description !!}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>    <!--== End Shop Area ==-->

@endsection
@push('scripts')
    <script>
        //for active btn
        $(document).ready(function () {
            $('[data-bs-toggle="tab"]').on('click', function (e) {
                e.preventDefault(); // منع السلوك الافتراضي

                var targetId = $(this).data('bs-target'); // الحصول على الهدف
                var $targetContent = $(targetId); // الحصول على محتوى التاب

                if ($targetContent.length) {
                    // إزالة الفئات 'show' و 'active' من التاب النشط حاليًا
                    $('.tab-pane.show.active').removeClass('show active');

                    // إضافة الفئات 'show' و 'active' إلى التاب الجديد
                    $targetContent.addClass('show active');
                }
            });
        });

        // ---------- Slider----------------
        // var swiperContainer = document.querySelector('.single-product-thumb-slider');
        // var swiperSlides = swiperContainer.querySelectorAll('.swiper-slide');

        mySwiper = new Swiper('.single-product-thumb-slider', {
            slidesPerView: 1,
            spaceBetween: 20,
            centeredSlides: true,
            loop: true, // قم بتفعيل خاصية loop فقط إذا كان هناك أكثر من صورة
            watchSlidesProgress: true, // لتحسين التتبع
            watchOverflow: true, // لإخفاء عناصر التنقل إذا كانت صورة واحدة

            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
        });

        Fancybox.bind("[data-fancybox]", {
            // تحسين إعدادات Fancybox
            Images: {
                zoom: true,
            },
            Toolbar: {
                display: [
                    "zoom",
                    "slideshow",
                    "fullscreen",
                    "close",
                ],
            },
            on: {
                reveal: () => {
                    mySwiper.loopDestroy();
                },
                closing: () => {
                    mySwiper.loopCreate();
                }
            },
            Carousel: {
                transition: "slide",
            },
            dragToClose: false,
            keyboard: {
                Escape: "close",
                Delete: "close",
                Backspace: "close",
                PageUp: "next",
                PageDown: "prev",
                ArrowUp: "next",
                ArrowDown: "prev",
                ArrowRight: "next",
                ArrowLeft: "prev",
            },
        });
    </script>


    {{--    Varations--}}
    <script>
        $(document).ready(function () {
            // التأكد من تعريف المتغيرات العامة التي تأتي من Laravel
            let variants, options, optionValuesGrouped, locale, currency;

            try {
                variants = {!! json_encode($variants) !!};
                options = {!! json_encode($options) !!};
                optionValuesGrouped = {!! json_encode($optionValuesGrouped) !!};
                locale = '{{ app()->getLocale() }}';
                currency = '{{ trim(__("home.currency")) }}';
            } catch (e) {
                console.error('Error initializing variables:', e);
                return;
            }

            // التأكد من أن كل المتغيرات المطلوبة موجودة
            if (!variants || !options) {
                console.error('Required variables are not defined');
                return;
            }

            function validateOptionsAndUpdateButton(selectedOptions) {
                const $addToCartBtn = $('.btn-add-cart');
                const requiredOptionsCount = options.length;
                const selectedOptionsCount = Object.keys(selectedOptions).length;

                if (selectedOptionsCount === requiredOptionsCount) {
                    const matchedVariant = findMatchingVariant(variants, selectedOptions);
                    $addToCartBtn.prop('disabled', !matchedVariant);
                    $addToCartBtn.css('opacity', matchedVariant ? '1' : '0.6');
                } else {
                    $addToCartBtn.prop('disabled', true);
                    $addToCartBtn.css('opacity', '0.6');
                }
            }

// دالة مساعدة للعثور على المتغير المطابق
            function findMatchingVariant(variants, selectedOptions) {
                return variants.find(variant =>
                    variant.option_values.every(vo =>
                        selectedOptions[vo.option_id] === parseInt(vo.id)
                    )
                );
            }
            // تعديل الحالة الأولية للزر عند تحميل الصفحة
            function initializeButtonState() {
                const selectedOptions = {};
                const $addToCartBtn = $('.btn-add-cart');

                // تعطيل الزر مبدئياً
                $addToCartBtn.prop('disabled', true).css('opacity', '0.6');
            }

            // إضافة استدعاء الدالة عند تحميل الصفحة
            initializeButtonState();

            function updateProductVariations() {
                const selectedOptions = {};

                $.each(options, function (_, option) {
                    const optionName = option.name['en'].toLowerCase();
                    let selectElement;

                    if (optionName === 'color') {
                        const selectedColorInput = $('.color-thumbnail input[type="radio"]:checked');
                        if (selectedColorInput.length) {
                            selectedOptions[option.id] = parseInt(selectedColorInput.val());
                        }
                    } else {
                        selectElement = $(`[name="product-${optionName}"]`);
                        if (selectElement.length && selectElement.val()) {
                            selectedOptions[option.id] = parseInt(selectElement.val());
                        }
                    }
                });

                $.each(options, function (_, option) {
                    const optionName = option.name['en'].toLowerCase();
                    if (optionName === 'color') return;

                    const selectElement = $(`[name="product-${optionName}"]`);
                    if (!selectElement.length) return;

                    const currentValue = selectElement.val();

                    // استخدام __ function من Laravel للترجمة
                    selectElement.html(`<option value="" selected>{{ __('products.choose') }} ${option.name[locale]}</option>`);

                    const availableOptionValues = filterAvailableOptionValues(variants, selectedOptions, option.id);

                    $.each(availableOptionValues, function (_, optionValue) {
                        const $option = $('<option>', {
                            value: optionValue.id,
                            text: optionValue.value[locale]
                        });

                        if (currentValue && parseInt(currentValue) === optionValue.id) {
                            $option.prop('selected', true);
                        }

                        selectElement.append($option);
                    });

                    if (!availableOptionValues.some(v => v.id == currentValue)) {
                        selectElement.val('');
                    }
                });

                updateProductDetails(variants, selectedOptions);
                validateOptionsAndUpdateButton(selectedOptions);
            }

            function filterAvailableOptionValues(variants, selectedOptions, currentOptionId) {
                const availableOptionValues = new Set();

                $.each(variants, function (_, variant) {
                    const variantOptionValues = variant.option_values;

                    const isValidVariant = Object.entries(selectedOptions)
                        .filter(([optionId, value]) =>
                            parseInt(optionId) !== currentOptionId && value !== null
                        )
                        .every(([optionId, value]) =>
                            variantOptionValues.some(vo =>
                                parseInt(vo.option_id) === parseInt(optionId) &&
                                parseInt(vo.id) === parseInt(value)
                            )
                        );

                    if (isValidVariant) {
                        $.each(variantOptionValues, function (_, vo) {
                            if (parseInt(vo.option_id) === currentOptionId) {
                                availableOptionValues.add(vo);
                            }
                        });
                    }
                });

                return Array.from(availableOptionValues);
            }

            function updateProductDetails(variants, selectedOptions) {
                const matchedVariant = variants.find(variant =>
                    variant.option_values.every(vo =>
                        !selectedOptions[vo.option_id] ||
                        parseInt(selectedOptions[vo.option_id]) === parseInt(vo.id)
                    )
                );

                if (matchedVariant) {
                    $('#selected_variant_id').val(matchedVariant.id);
                    updateProductImages(matchedVariant);
                    updateProductPrice(matchedVariant);
                }
            }

            function updateProductImages(variant) {
                const $imageContainer = $('.single-product-thumb-slider .swiper-wrapper');

                if (variant.images && variant.images.length) {
                    $imageContainer.empty();
                    const galleryId = 'gallery-' + $.now();

                    $.each(variant.images, function (_, image) {
                        const $slideElement = $('<div>').addClass('swiper-slide');
                        const $thumbElement = $('<div>').addClass('thumb-item');
                        const $linkElement = $('<a>').attr({
                            href: '{{ asset("storage") }}/' + image.path,
                            'data-fancybox': galleryId
                        });
                        const $imgElement = $('<img>').attr({
                            src: '{{ asset("storage") }}/' + image.path,
                            alt: 'Product Image'
                        });

                        $linkElement.append($imgElement);
                        $thumbElement.append($linkElement);
                        $slideElement.append($thumbElement);
                        $imageContainer.append($slideElement);
                    });

                    // إذا كان Swiper موجود، قم بتدميره قبل إنشاء واحد جديد
                    if (typeof mySwiper !== 'undefined') {
                        mySwiper.destroy();
                    }

                    mySwiper = new Swiper('.single-product-thumb-slider', {
                        slidesPerView: 1,
                        spaceBetween: 20,
                        centeredSlides: true,
                        loop: variant.images.length > 1,
                        watchSlidesProgress: true,
                        watchOverflow: true,
                        navigation: {
                            nextEl: '.swiper-button-next',
                            prevEl: '.swiper-button-prev'
                        },
                        pagination: {
                            el: '.swiper-pagination',
                            clickable: true
                        }
                    });

                    // تهيئة Fancybox
                    Fancybox.bind("[data-fancybox]", {
                        Images: {
                            zoom: true
                        },
                        Toolbar: {
                            display: [
                                "zoom",
                                "slideshow",
                                "fullscreen",
                                "close"
                            ]
                        },
                        on: {
                            reveal: function () {
                                mySwiper && mySwiper.loopDestroy();
                            },
                            closing: function () {
                                mySwiper && mySwiper.loopCreate();
                            }
                        },
                        Carousel: {
                            transition: "slide"
                        },
                        dragToClose: false,
                        keyboard: {
                            Escape: "close",
                            Delete: "close",
                            Backspace: "close",
                            PageUp: "next",
                            PageDown: "prev",
                            ArrowUp: "next",
                            ArrowDown: "prev",
                            ArrowRight: "next",
                            ArrowLeft: "prev"
                        }
                    });
                }
            }

            function updateProductPrice(variant) {
                const $pricesContainer = $('.prices');

                if ($pricesContainer.length) {
                    const originalPrice = parseFloat(variant.price).toFixed(2);
                    const discountedPrice = parseFloat(variant.discounted_price).toFixed(2);

                    let priceHtml = '';
                    if (parseFloat(originalPrice) > parseFloat(discountedPrice)) {
                        priceHtml = `
        <span class="price-old" style="color: #999;">
            ${parseFloat(originalPrice).toLocaleString(undefined, {
                            minimumFractionDigits: 2,
                            maximumFractionDigits: 2
                        })} ${currency}
        </span>
        <span class="price" style="color: #e74c3c;">
            ${parseFloat(discountedPrice).toLocaleString(undefined, {
                            minimumFractionDigits: 2,
                            maximumFractionDigits: 2
                        })} ${currency}
        </span>
    `;
                    } else {
                        priceHtml = `
        <span class="price" style="color: #e74c3c;">
            ${parseFloat(discountedPrice).toLocaleString(undefined, {
                            minimumFractionDigits: 2,
                            maximumFractionDigits: 2
                        })} ${currency}
        </span>
    `;
                    }

                    $pricesContainer.html(priceHtml);
                }
            }

            function activateDefaultColor() {
                const firstColorOption = options.find(option =>
                    option.name['en'].toLowerCase() === 'color'
                );

                if (firstColorOption) {
                    const $firstColorThumbnail = $('.color-thumbnail').first();
                    if ($firstColorThumbnail.length) {
                        $firstColorThumbnail.addClass('selected');
                        const $firstRadioInput = $firstColorThumbnail.find('input[type="radio"]');
                        if ($firstRadioInput.length) {
                            $firstRadioInput.prop('checked', true);
                            updateProductVariations();
                        }
                    }
                }
            }

            // تسجيل Event Handlers
            $.each(options, function (_, option) {
                const optionName = option.name['en'].toLowerCase();
                if (optionName !== 'color') {
                    const $selectElement = $(`[name="product-${optionName}"]`);
                    if ($selectElement.length) {
                        $selectElement.on('change', updateProductVariations);
                    }
                }
            });
// تحسين معالج حدث النقر على الألوان
            $('.color-thumbnail').on('click', function() {
                const $radio = $(this).find('input[type="radio"]');
                if (!$radio.prop('checked')) {
                    $('.color-thumbnail').removeClass('selected');
                    $(this).addClass('selected');
                    $radio.prop('checked', true);
                    updateProductVariations();
                }
            });

            // تهيئة الصفحة
            activateDefaultColor();
            updateProductVariations();
        });
    </script>
@endpush
@push('styles')
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css"/>
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@4.0/dist/fancybox.css"/>
    <script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@4.0/dist/fancybox.umd.js"></script>

    <style>
        /* ===== Product Slider Styles ===== */
        .single-product-slider {
            width: 100%;
            max-width: 100%;
            margin: 0 auto;
        }

        .single-product-thumb-slider {
            height: auto;
            padding: 0 50px;
            margin: 20px 0;
            position: relative;
        }

        .single-product-thumb-slider .swiper-wrapper {
            height: 100%;
            align-items: center;
        }

        .swiper-slide {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100%;
            height: 100%;
        }

        .thumb-item {
            position: relative;
            width: 100%;
            height: 0;
            padding-top: 75%; /* 4:3 Aspect Ratio */
            overflow: hidden;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .thumb-item img {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: contain;
        }


        /* ===== Navigation Styles ===== */
        .swiper-button-next,
        .swiper-button-prev {
            width: 40px;
            height: 40px;
            background-color: rgba(255, 255, 255, 0.9);
            border-radius: 50%;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .swiper-button-next::after,
        .swiper-button-prev::after {
            font-size: 18px;
            color: #333;
        }

        .swiper-pagination {
            position: relative;
            bottom: -10px;
            margin-top: 15px;
        }

        .swiper-pagination-bullet {
            width: 8px;
            height: 8px;
            margin: 0 5px;
            background-color: #999;
            opacity: 0.5;
        }

        .swiper-pagination-bullet-active {
            opacity: 1;
            background-color: #333;
        }

        /* ===== Product Info Styles ===== */
        .single-product-info {
            margin-top: 20px;
        }

        .single-product-info .title {
            font-size: 24px;
            margin-bottom: 10px;
        }

        .prices {
            font-size: 18px;
            margin-bottom: 15px;
        }

        .price-old {
            position: relative;
            color: #999;
            font-size: 13px;
            text-decoration: unset !important;
        }

        .price-old::after {
            content: '';
            position: absolute;
            left: 6%;
            top: 50%;
            width: 94%;
            height: .5px;
            background-color: black;
            transform: translateY(-50%);
        }

        /* ===== Product Actions ===== */
        .quick-product-action {
            margin: 35px 0 20px;
        }

        .single-product-info .quick-product-action .btn-theme {
            margin-inline-end: 5px !important;
        }

        .single-product-info .quick-product-action .btn-wishlist {
            color: white !important;
        }

        .single-product-info .quick-product-action .btn-wishlist:before {
            content: unset;
        }

        /* ===== Fancybox Customization ===== */
        .fancybox__container {
            --fancybox-bg: rgba(24, 24, 27, 0.95);
        }

        .fancybox__content {
            padding: 0;
            background: transparent;
            max-height: 90vh;
            margin: auto;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .fancybox__image {
            object-fit: contain;
            max-width: 90vw;
            max-height: 90vh;
        }

        .fancybox__carousel {
            align-items: center;
        }

        /* ===== Responsive Styles ===== */
        /* Mobile Styles */
        @media (max-width: 767px) {
            .single-product-thumb-slider {
                height: 300px;
                padding: 0 30px;
            }

            .thumb-item {
                padding-top: 100%; /* Square aspect ratio for mobile */
            }

            .single-product-info .title {
                font-size: 1.2rem;
            }

            .quick-product-action {
                display: flex;
                flex-direction: column;
                align-items: center;
            }

            .pro-qty, .btn-theme, .btn-wishlist {
                margin-bottom: 10px;
                width: 100%;
                text-align: center;
            }
        }

        /* Desktop Styles */
        @media (min-width: 768px) {
            .single-product-thumb-slider {
                min-height: 400px;
            }

            .thumb-item {
                padding-top: 100%; /* Square aspect ratio for desktop */
            }

            .quick-product-action .action-top {
                display: flex;
                align-items: center;
                gap: 15px;
            }

            .pro-qty input {
                padding-inline-start: 10px !important;
                padding-inline-end: 10px !important;
            }

            .single-product-info .quick-product-action .btn-theme {
                padding: 5px 16px !important;
            }
        }

        @media (min-width: 992px) {
            .single-product-thumb-slider {
                min-height: 450px;
            }

            .product-description-review {
                padding-top: 20px !important;
                padding-bottom: 50px !important;
            }
        }

        .color-thumbnail {
            border: 1px solid transparent;
            transition: border-color 0.3s ease;
        }

        .color-thumbnail.selected {
            border-color: #007bff; /* Change to your preferred highlight color */
        }

        .color-thumbnail img {
            height: 60px;
            width: 65px;
        }

        @media (max-width: 767px) {
            .btn-product-qty {
                padding-inline-end: 35px !important;
                margin-inline-end: -25px;
                margin-inline-start: -7px;
            }
        }

        .btn-product-cart {
            padding-inline-end: 0 !important;
            padding-inline-start: 8px !important;
        }
    </style>
@endpush

