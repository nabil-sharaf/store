<!--=======================Javascript============================-->

<!--=== Modernizr Min Js ===-->
<script async src="https://cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.3/modernizr.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<!--=== Bootstrap Min Js ===-->
<script src="{{asset('front/assets')}}/js/bootstrap.min.js"></script>
<!--=== jquery Appear Js ===-->
<script src="{{asset('front/assets')}}/js/jquery.appear.js"></script>
<!--=== jquery Swiper Min Js ===-->
<script src="{{asset('front/assets')}}/js/swiper.min.js"></script>
<!--=== jquery Fancybox Min Js ===-->
<script src="{{asset('front/assets')}}/js/fancybox.min.js"></script>
<!--=== jquery Aos Min Js ===-->
<script src="{{asset('front/assets')}}/js/aos.min.js"></script>
<!--=== jquery Slicknav Js ===-->
<script src="{{asset('front/assets')}}/js/jquery.slicknav.js"></script>
<!--=== jquery Countdown Js ===-->
<script src="{{asset('front/assets')}}/js/jquery.countdown.min.js"></script>
<!--=== jquery Tippy Js ===-->
<script src="{{asset('front/assets')}}/js/tippy.all.min.js"></script>
<!--=== Isotope Min Js ===-->
<script src="{{asset('front/assets')}}/js/isotope.pkgd.min.js"></script>
<!--=== Parallax Min Js ===-->
<script src="{{asset('front/assets')}}/js/parallax.min.js"></script>
<!--=== Slick  Min Js ===-->
<script src="{{asset('front/assets')}}/js/slick.min.js"></script>
<!--=== jquery Wow Min Js ===-->
<script src="{{asset('front/assets')}}/js/wow.min.js"></script>
<!--=== jquery Zoom Min Js ===-->
<script src="{{asset('front/assets')}}/js/jquery-zoom.min.js"></script>

<!--=== Custom Js ===-->
@if (LaravelLocalization::getCurrentLocale() == 'ar')
    <script src="{{asset('front/assets')}}/js/custom_ar.js"></script>
@else
    <script src="{{asset('front/assets')}}/js/custom.js"></script>
@endif

{{--toastr--}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>


<script>
    //عرض واخفاء مودال البوتستراب للمنتج في الرئيسية
    $(document).ready(function () {
        // إغلاق المودال عند النقر على زر الإغلاق أو على التراكب
        $(document).ready(function() {
            // استخدام event delegation بدلاً من تعيين الأحداث لكل عنصر
            $(document).on('click', '.btn-close, .canvas-overlay', function() {
                $('.product-quick-view-modal, .canvas-overlay').fadeOut();
            });
        });

        updateCartDetails();

    });
    let currentProductData = null;

    // تحديث دالة عرض تفاصيل المنتج لتعامل مع صور الفاريانت الافتراضية
    // تعديل دالة showProductDetails لتنظيف المودال قبل عرض المنتج الجديد
    // دالة جديدة لتنظيف المودال
    function resetModal() {
        // إعادة تعيين محتويات المودال
        $('.product-quick-view-modal .product-name').text('');
        $('.product-quick-view-modal .product-desc').html('');
        $('.product-quick-view-modal .prices').empty();
        $('.product-quick-view-modal .product-categories').empty();
        $('.product-images-slider').empty();
        $('.variant-section').empty();
        $('.quick-product-action button').prop('disabled', true);
        // تأكيد إخفاء وإعادة ضبط الورنينج
        $('.variant-warning')
            .hide()
            .text('* برجاء اختيار جميع خصائص المنتج من أجل الإضافة للسلة');
    }

    function showProductDetails(productId) {
        // Cache DOM selections
        const $modal = $('.product-quick-view-modal');
        const $categoriesContainer = $modal.find('.product-categories');
        const $sliderContainer = $('.product-images-slider');
        const $quickProductAction = $('.quick-product-action');

        const myUrl = "{{route('product.details',':productId')}}".replace(':productId', productId);

        // Reset and show modal
        resetModal();
        $modal.add('.canvas-overlay').fadeIn(200);

        // Disable add to cart button
        $quickProductAction.find('button').prop('disabled', true);

        // Check cache first
        const cachedProduct = productCache.get(productId);
        if (cachedProduct) {
            processResponse(cachedProduct);
            return;
        }

        $.ajax({
            url: myUrl,
            method: 'GET',
            cache: true
        })
            .done(function(response) {
                productCache.set(productId, response);
                processResponse(response);
            })
            .fail(function(error) {
                console.error('Error fetching product details:', error);
                toastr.error('حدث خطأ أثناء تحميل تفاصيل المنتج');
            });
    }

    // نظام التخزين المؤقت للمنتجات
    const productCache = {
        data: new Map(),
        maxAge: 5 * 60 * 1000, // 5 دقائق

        set: function(id, data) {
            this.data.set(id, {
                timestamp: Date.now(),
                data: data
            });
        },

        get: function(id) {
            const item = this.data.get(id);
            if (!item) return null;

            if (Date.now() - item.timestamp > this.maxAge) {
                this.data.delete(id);
                return null;
            }

            return item.data;
        },

        clear: function() {
            this.data.clear();
        }
    };

    // دالة معالجة الاستجابة منفصلة عن showProductDetails
    function processResponse(response) {
        const $modal = $('.product-quick-view-modal');
        const $categoriesContainer = $modal.find('.product-categories');
        const $sliderContainer = $('.product-images-slider');
        const $quickProductAction = $('.quick-product-action');

        currentProductData = response;

        // تحديث البيانات الأساسية
        $modal.find('.product-name').text(response.name);
        $modal.find('.product-desc').html(response.description);

        // معالجة الصور
        if (response.images && response.images.length > 0) {
            displayImages(response.images, $sliderContainer);
        }

        // معالجة المتغيرات والخيارات
        if (response.variants && response.variants.length > 0) {
            if (response.available_options && response.available_options.length > 0) {
                handleProductOptions(response);
            } else {
                const variant = response.variants[0];
                updatePriceDisplay(variant.price, variant.discounted_price);
                enableAddToCartButton(response.id, variant.id);
            }
        } else {
            updatePriceDisplay(response.price, response.discounted_price);
            enableAddToCartButton(response.id, null);
        }

        // تحديث باقي العناصر
        displayCategories(response.categories, $categoriesContainer);
        $quickProductAction.find('.btn-wishlist')
            .attr('onclick', `wishListAdd(event,this,${response.id})`);

        updateQuantityField(response.id);
        $modal.show();
    }

    function handleProductOptions(response) {
        const $variantSection = $('.variant-section');
        const $variantWarning = $('.variant-warning');

        $variantSection.empty();

        // التحقق من وجود variants و options
        if (!response.variants || response.variants.length === 0 ||
            !response.available_options || response.available_options.length === 0) {
            $variantWarning.hide();
            return;
        }

        // إظهار/إخفاء رسالة التحذير
        if(response.warning_message === true) {
            $variantWarning.show();
        } else {
            $variantWarning.hide();
        }

        // ترتيب الخيارات
        const sortedOptions = response.available_options.sort((a, b) => a.position - b.position);
        const firstOption = sortedOptions[0];
        const firstValues = getAvailableValuesForOption(response.variants, firstOption.id);

        // إنشاء وإضافة الخيار الأول
        const $firstSelect = $(createOptionSelect(firstOption, firstValues));
        $variantSection.append($firstSelect);

        // إضافة event listener للخيار الأول
        $variantSection.find('.option-selector').first().on('change', function() {
            const selectedValue = $(this).val();
            const $addToCartBtn = $('.quick-product-action button');

            $addToCartBtn
                .prop('disabled', true)
                .html(`أضف للسلة &nbsp; <i class="fa fa-shopping-cart"></i>`);

            if (selectedValue) {
                updateNextOptions(response, sortedOptions, 1, {
                    [firstOption.id]: selectedValue
                });
            } else {
                $variantSection.find('.option-selector')
                    .not(':first')
                    .closest('.form-group')
                    .remove();
            }
        });
    }

    function handleOptionChange(productId) {
        const selectedOptions = {};
        let allOptionsSelected = true;

        $('.option-selector').each(function() {
            const optionId = $(this).data('option-id');
            const valueId = $(this).val();

            if (!valueId) {
                allOptionsSelected = false;
            }
            selectedOptions[optionId] = valueId;
        });

        // تعطيل الزر إذا لم يتم اختيار كل الخيارات
        if (!allOptionsSelected) {
            $('.quick-product-action button')
                .prop('disabled', true)
                .html(`أضف للسلة &nbsp; <i class="fa fa-shopping-cart"></i>`);

            if (currentProductData.images && currentProductData.images.length > 0) {
                displayImages(currentProductData.images, $('.product-images-slider'));
            }
            return;
        }

        const matchingVariant = findMatchingVariant(selectedOptions);

        if (matchingVariant) {
            updatePriceDisplay(matchingVariant.price, matchingVariant.discounted_price);

            if (matchingVariant.images && matchingVariant.images.length > 0) {
                displayImages(matchingVariant.images, $('.product-images-slider'));
            }

            if (matchingVariant.quantity > 0) {
                $('.quick-product-action').find('.variant-id-holder').remove();
                $('.quick-product-action').prepend(`<input type="hidden" class="variant-id-holder" value="${matchingVariant.id}">`);

                $('.quick-product-action button')
                    .prop('disabled', false)
                    .html(`أضف للسلة &nbsp; <i class="fa fa-shopping-cart"></i>`)
                    .attr('onclick', `addToCart(event, ${productId}, document.getElementById('quantity_${productId}').value, ${matchingVariant.id})`);
            } else {
                $('.quick-product-action button')
                    .prop('disabled', true)
                    .text('غير متوفر حالياً');
            }
        } else {
            toastr.error('هذه المجموعة من الخيارات غير متوفرة');
            $('.quick-product-action button').prop('disabled', true);
            if (currentProductData.images && currentProductData.images.length > 0) {
                displayImages(currentProductData.images, $('.product-images-slider'));
            }
        }
    }

    function updateNextOptions(response, sortedOptions, currentIndex, selectedOptions) {
        const variantSection = $('.variant-section');

        // إزالة جميع الخيارات التالية للمستوى الحالي
        variantSection.find('.option-selector').slice(currentIndex).closest('.form-group').remove();

        if (currentIndex >= sortedOptions.length) {
            // تم اختيار جميع الخيارات، البحث عن الفاريانت المطابق
            handleOptionChange(response.id);
            return;
        }

        // الحصول على الفاريانتس المتاحة بناءً على الاختيارات السابقة
        const availableVariants = getAvailableVariants(response.variants, selectedOptions);

        if (availableVariants.length === 0) {
            return;
        }

        // إنشاء الخيار التالي
        const nextOption = sortedOptions[currentIndex];
        const availableValues = getAvailableValuesForOptionFiltered(availableVariants, nextOption.id);

        if (availableValues.length > 0) {
            const nextSelect = createOptionSelect(nextOption, availableValues);
            variantSection.append(nextSelect);

            // إضافة event listener للخيار الجديد
            variantSection.find('.option-selector').last().on('change', function() {
                const value = $(this).val();
                if (value) {
                    const newSelectedOptions = { ...selectedOptions, [nextOption.id]: value };
                    updateNextOptions(response, sortedOptions, currentIndex + 1, newSelectedOptions);
                } else {
                    // إزالة الخيارات التالية عند إعادة الاختيار
                    variantSection.find('.option-selector').slice(currentIndex + 1).closest('.form-group').remove();
                    $('.quick-product-action button').prop('disabled', true);
                }
            });
        }
    }

    function getAvailableValuesForOption(variants, optionId) {
        const values = new Set();
        variants.forEach(variant => {
            const optionValue = variant.option_values.find(ov => ov.option_id === optionId);
            if (optionValue) {
                values.add(JSON.stringify({
                    id: optionValue.id,
                    value: optionValue.value
                }));
            }
        });
        return Array.from(values).map(v => JSON.parse(v));
    }

    function getAvailableValuesForOptionFiltered(variants, optionId) {
        const values = new Set();
        variants.forEach(variant => {
            const optionValue = variant.option_values.find(ov => ov.option_id === optionId);
            if (optionValue) {
                values.add(JSON.stringify({
                    id: optionValue.id,
                    value: optionValue.value
                }));
            }
        });
        return Array.from(values).map(v => JSON.parse(v));
    }

    function getAvailableVariants(variants, selectedOptions) {
        return variants.filter(variant => {
            return Object.entries(selectedOptions).every(([optionId, valueId]) => {
                const optionValue = variant.option_values.find(ov => ov.option_id === parseInt(optionId));
                return optionValue && optionValue.id.toString() === valueId;
            });
        });
    }

    function createOptionSelect(option, values) {
        return $(`
        <div class="form-group mb-3">
            <label>${option.name}:</label>
            <select class="form-control option-selector" data-option-id="${option.id}">
                <option value="">اختر ${option.name}</option>
                ${values.map(value => `
                    <option value="${value.id}">${value.value}</option>
                `).join('')}
            </select>
        </div>
    `);
    }


    function findMatchingVariant(selectedOptions) {
        if (!currentProductData || !currentProductData.variants) return null;

        return currentProductData.variants.find(variant => {
            const variantOptionValues = variant.option_values.reduce((acc, val) => {
                acc[val.option_id] = val.id.toString();
                return acc;
            }, {});

            return Object.entries(selectedOptions).every(([optionId, valueId]) =>
                variantOptionValues[optionId] === valueId
            );
        });
    }

    function updatePriceDisplay(price, discountedPrice) {
        // تخزين العناصر مؤقتاً لتحسين الأداء
        const pricesContainer = $('.product-quick-view-modal .prices');
        const currency = "{{ __('home.currency') }}";

        // تنظيف الحاوية
        pricesContainer.empty();

        // التأكد من أن الأسعار أرقام صحيحة
        const formattedPrice = parseFloat(price).toFixed(2);
        const formattedDiscountedPrice = parseFloat(discountedPrice).toFixed(2);

        // استخدام التجميع للتحديثات المتعددة
        const markup = (formattedPrice === formattedDiscountedPrice)
            ? `<span class="price" style="color: #e74c3c;">
             ${formattedPrice} ${currency}
           </span>`
            : `<span class="price-old" style="text-decoration: line-through; color: #999; font-size:18px;">
             ${formattedPrice} ${currency}
           </span>
           <span class="price mx-2" style="color: #e74c3c;">
             ${formattedDiscountedPrice} ${currency}
           </span>
           <span class="discount-percentage" style="background: #e74c3c; color: white; padding: 2px 8px; border-radius: 4px; font-size: 14px;">
             ${calculateDiscount(formattedPrice, formattedDiscountedPrice)}% خصم
           </span>`;

        // تحديث DOM مرة واحدة فقط
        pricesContainer.html(markup);
    }

    // دالة مساعدة لحساب نسبة الخصم
    function calculateDiscount(originalPrice, discountedPrice) {
        const discount = ((originalPrice - discountedPrice) / originalPrice) * 100;
        return Math.round(discount); // تقريب النسبة لأقرب رقم صحيح
    }

    function displayCategories(categories, container) {
        container.empty();
        categories.forEach(function(cat) {
            container.append(`<a href="#" class="mx-2">${cat.name}</a>`);
        });
    }

    function updateImage(imagePath) {
        $('.product-images-slider').html(`
        <div class="swiper-slide">
            <img src="{{ asset('storage') }}/${imagePath}" alt="Product Image" />
        </div>
    `);
    }

    function updateQuantityField(productId) {
        $('.pro-qty input')
            .attr('id', `quantity_${productId}`)
            .val(1);
    }

    function enableAddToCartButton(productId, variantId) {
        const quantitySelector = `document.getElementById('quantity_${productId}').value`;
        const onClickFunction = variantId
            ? `addToCart(event, ${productId}, ${quantitySelector}, ${variantId})`
            : `addToCart(event, ${productId}, ${quantitySelector})`;

        $('.quick-product-action button')
            .prop('disabled', false)
            .html(`أضف للسلة &nbsp; <i class="fa fa-shopping-cart"></i>`)
            .attr('onclick', onClickFunction);
    }

    // تحديث دالة عرض الصور لتدعم عرض أكثر من صورة
    function displayImages(images, container) {
        // التحقق من المدخلات في البداية
        if (!container || !container.length) return;

        container.empty();

        if (!images || !images.length) {
            container.html(`
            <div class="product-gallery">
                <div class="main-image-slider">
                    <div class="swiper-slide">
                        <div class="image-wrapper">
                            <p>لا توجد صورة متاحة</p>
                        </div>
                    </div>
                </div>
            </div>
        `);
            return;
        }

        // تجهيز القوالب مرة واحدة
        const baseImageUrl = "{{ asset('storage') }}/";
        const mainSlides = images.map(image =>
            `<div class="swiper-slide">
            <div class="image-wrapper">
                <img loading="lazy" src="${baseImageUrl}${image.path}" alt="Product Image" />
            </div>
        </div>`
        ).join('');

        const thumbnailSlides = images.length > 1 ? images.map(image =>
            `<div class="swiper-slide">
            <div class="thumb-image">
                <img src="${baseImageUrl}${image.path}" alt="Thumbnail" />
            </div>
        </div>`
        ).join('') : '';

        // تجميع HTML مرة واحدة
        const galleryHTML = `
        <div class="product-gallery">
            <div class="main-image-slider">
                <div class="swiper-wrapper">
                    ${mainSlides}
                </div>
                <div class="swiper-button-next"></div>
                <div class="swiper-button-prev"></div>
            </div>
            ${images.length > 1 ? `
                <div class="thumbnail-slider">
                    <div class="swiper-wrapper">
                        ${thumbnailSlides}
                    </div>
                </div>
            ` : ''}
        </div>
    `;

        // إضافة HTML مرة واحدة
        container.html(galleryHTML);

        // تهيئة السلايدر الرئيسي مع إعدادات محسنة
        const mainSlider = new Swiper('.main-image-slider', {
            slidesPerView: 1,
            spaceBetween: 0,
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
            observer: true,
            observeParents: true,
            preloadImages: false,
            lazy: true,
            watchSlidesProgress: true
        });

        // تهيئة سلايدر المصغرات فقط إذا كان هناك أكثر من صورة
        if (images.length > 1) {
            const thumbSlider = new Swiper('.thumbnail-slider', {
                slidesPerView: 4,
                spaceBetween: 10,
                observer: true,
                observeParents: true,
                watchSlidesProgress: true,
                touchRatio: 0.2,
                slideToClickedSlide: true,
                preloadImages: false,
                lazy: true,
                breakpoints: {
                    320: {
                        slidesPerView: 3,
                        spaceBetween: 5,
                    },
                    768: {
                        slidesPerView: 4,
                        spaceBetween: 10,
                    }
                }
            });

            // ربط السلايدرز بشكل محسن
            mainSlider.controller.control = thumbSlider;
            thumbSlider.controller.control = mainSlider;

            // استخدام event delegation للنقر على الصور المصغرة
            $('.thumbnail-slider').on('click', '.swiper-slide', function() {
                mainSlider.slideTo($(this).index());
            });

            // تحسين أداء تحديث الصورة النشطة
            const $thumbnails = $('.thumbnail-slider .swiper-slide');
            mainSlider.on('slideChange', () => {
                $thumbnails.removeClass('thumb-active')
                    .eq(mainSlider.activeIndex)
                    .addClass('thumb-active');
            });
        }
    }
    // إضافة CSS المحسن للتنسيق
    // إضافة CSS للتأكد من تنسيق السلايدر
    const style = document.createElement('style');
    style.textContent = `
    .product-gallery {
        width: 100%;
        max-width: 500px;
        margin: 0 auto;
        padding: 15px;
        position: relative;
        direction: rtl;
    }

    .main-image-slider {
        width: 100%;
        border-radius: 8px;
        margin-bottom: 15px;
        background: #fff;
        overflow: hidden;
        position: relative;
    }

    .main-image-slider .swiper-slide {
        height: 350px;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
    }

    .image-wrapper {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 10px;
        position: relative;
    }

    .image-wrapper img {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
        border-radius: 4px;
    }

    /* تنسيق أزرار التنقل */
    .main-image-slider .swiper-button-next,
    .main-image-slider .swiper-button-prev {
        width: 32px;
        height: 32px;
        background: rgba(255, 255, 255, 0.9);
        border-radius: 50%;
        box-shadow: 0 2px 6px rgba(0,0,0,0.2);
        top: 50%;
        transform: translateY(-50%);
        z-index: 10;
        position: absolute;
    }

    /* تنسيق السهم نفسه */
    .main-image-slider .swiper-button-next:after,
    .main-image-slider .swiper-button-prev:after {
        font-size: 14px !important;
        font-weight: bold;
        color: #333;
        transform: scale(0.7);
    }

    /* تبديل اتجاهات الأسهم للغة العربية */
    .main-image-slider .swiper-button-prev {
        right: 10px !important;
        left: auto !important;
    }

    .main-image-slider .swiper-button-next {
        left: 10px !important;
        right: auto !important;
    }

    .main-image-slider .swiper-button-next:after {
        content: 'prev' !important;
    }

    .main-image-slider .swiper-button-prev:after {
        content: 'next' !important;
    }

    /* تحسين التفاعلية */
    .main-image-slider .swiper-button-next:hover,
    .main-image-slider .swiper-button-prev:hover {
        background: rgba(255, 255, 255, 1);
        box-shadow: 0 3px 8px rgba(0,0,0,0.3);
    }

    .thumbnail-slider {
        width: 100%;
        height: 80px;
        padding: 5px 0;
        margin-top: 15px;
    }

    .thumbnail-slider .swiper-slide {
        opacity: 0.6;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .thumbnail-slider .swiper-slide-active {
        opacity: 1;
    }

    .thumb-image {
        height: 70px;
        padding: 5px;
        border: 1px solid #ddd;
        border-radius: 4px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .thumb-image img {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
    }

    /* تعديلات الموبايل */
    @media (max-width: 768px) {
        .main-image-slider .swiper-slide {
            height: 250px;
        }

        .main-image-slider .swiper-button-next,
        .main-image-slider .swiper-button-prev {
            width: 28px;
            height: 28px;
        }

        .main-image-slider .swiper-button-next:after,
        .main-image-slider .swiper-button-prev:after {
            font-size: 12px !important;
            transform: scale(0.6);
        }

        .thumbnail-slider {
            height: 60px;
        }

        .thumb-image {
            height: 50px;
        }
    }

    .product-quick-view-modal .modal-body {
        padding: 20px;
        position: relative;
    }

    .product-quick-view-modal .product-info {
        padding-top: 20px;
        position: relative;
        z-index: 1;
    }
    .thumbnail-slider .swiper-slide {
    opacity: 0.6;
    cursor: pointer;
    transition: all 0.3s ease;
    position: relative;
}

.thumbnail-slider .swiper-slide:hover {
    opacity: 0.8;
}

.thumbnail-slider .swiper-slide-active,
.thumbnail-slider .swiper-slide.thumb-active {
    opacity: 1;
}


.thumbnail-slider .swiper-slide.thumb-active .thumb-image {
    border:1px solid #e74c3c;
}

.thumb-image {
    height: 70px;
    padding: 3px;
    border-radius: 4px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
}

.thumb-image:hover {
    border-color: #999;
}

@media (max-width: 768px) {
    .thumb-image {
        height: 50px;
        padding: 2px;
    }
}
.option-selector{
    padding-right:60px !important;
    font-weight:600 !important;
}

`;
    document.head.appendChild(style);

    // ----------------------------Wish List-------------------------------

    // إضافة المنتجات للويش ليست
    function wishListAdd(event, element, id = null) {
        // منع الإجراء الافتراضي للحدث
        event.preventDefault();

        // تحديد زر قائمة الرغبات الحالي
        const wishlistButton = $(element);

        // التحقق مما إذا كان هناك طلب جاري بالفعل
        if (wishlistButton.data('loading')) {
            return;
        }

        // تحديد معرف المنتج
        let productId;
        if (id) {
            productId = id;
        } else {
            productId = wishlistButton.data('id');
        }

        // إنشاء رابط إضافة المنتج إلى قائمة الرغبات
        const url = "{{route('wishlist.store',':productId')}}".replace(':productId', productId);

        // تعطيل الزر وإضافة حالة التحميل
        wishlistButton
            .data('loading', true)
            .prop('disabled', true)
            .addClass('btn-loading');

        // إرسال الطلب
        $.ajax({
            url: url,
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}'
            },
            complete: function() {
                // إعادة تمكين الزر وإزالة حالة التحميل
                wishlistButton
                    .data('loading', false)
                    .prop('disabled', false)
                    .removeClass('btn-loading');
            },
            success: function (response) {
                if (response.message) {
                    toastr.success(response.message);
                    console.log(productId);
                }
                if (response.err) {
                    toastr.error(response.err);
                }
            },
            error: function (xhr) {
                console.log(productId);
                if (xhr.status === 401) {
                    toastr.warning('{{ __('scripts.login_required') }}');
                } else {
                    toastr.error('{{ __('scripts.error') }}', 'خطأ');
                    console.log(xhr);
                }
            }
        });
    }
    function wishListMessage(event) {
        // عرض رسالة اذا لم يكن المستخدم مسجل دخول
        // منع إعادة توجيه الرابط
        event.preventDefault();

        // عرض رسالة باستخدام Toastr
        toastr.warning('{{ __('scripts.wishlist_message') }}');
    }


    // ------------------------------Begin Shopping Cart--------------------------------
    // ------------------------------Begin Shopping Cart--------------------------------

    // المتغيرات العامة
    const csrf_token = '{{ csrf_token() }}';
    const currency = '{{ __("scripts.currency_symbol") }}';
    const storageUrl = '{{ asset("storage/") }}';
    const freeItemText = '{{ __("shop-cart.free_item") }}';

    // روابط
    const cartAddRoute = '{{ route("cart.add") }}';
    const cartDetailsRoute = '{{ route("cart.details") }}';
    const cartUpdateRoute = '{{ route("cart.update") }}';
    const cartRemoveRoute = '{{ route("cart.remove") }}';

    // رسائل
    const cartUpdateSuccessMessage = '{{ __("scripts.cart_update_success") }}';
    const cartUpdateErrorMessage = '{{ __("scripts.cart_update_error") }}';
    const cartDetailsErrorMessage = '{{ __("scripts.cart_details_error") }}';
    const cartRemoveSuccessMessage = '{{ __("scripts.cart_remove_success") }}';
    const cartRemoveErrorMessage = '{{ __("scripts.cart_remove_error") }}';

    // إضافة منتج إلى السلة
    // التحقق من اختيار جميع الخيارات المطلوبة
    function validateVariantSelection(options) {
        let isValid = true;
        let message = '';

        // التحقق من وجود خيارات للمنتج
        if (options && options.length > 0) {
            $.each(options, function (_, option) {
                const optionName = option.name[locale];
                const optionNameEn = option.name['en'].toLowerCase();

                if (optionNameEn === 'color') {
                    // التحقق من اختيار اللون
                    const selectedColor = $('.color-thumbnail input[type="radio"]:checked');
                    if (!selectedColor.length) {
                        isValid = false;
                        message = `${__('products.please_select')} ${optionName}`;
                        return false; // للخروج من each loop
                    }
                } else {
                    // التحقق من اختيار القيمة في السيليكت
                    const selectElement = $(`[name="product-${optionNameEn}"]`);
                    if (selectElement.length && !selectElement.val()) {
                        isValid = false;
                        message = `${__('products.please_select')} ${optionName}`;
                        return false; // للخروج من each loop
                    }
                }
            });
        }

        return {isValid, message};
    }

    // دالة إضافة المنتج للسلة
    // تحديث دالة addToCart لاستخدام معرف الفاريانت بشكل صحيح
    function addToCart(event, productId, quantity = 1, variantId = null) {
        // Prevent default form submission
        event.preventDefault();

        // Select the current add to cart button
        const addToCartButton = $(event.target);

        // Check if a request is already in progress
        if (addToCartButton.data('loading')) {
            return;
        }

        // Validate quantity with more strict checks
        quantity = Math.max(1, parseInt(quantity) || 1);

        // If variant ID is not provided, try to find it more reliably
        if (!variantId) {
            variantId = findVariantId(productId);
        }

        // Prepare data object for the request
        const data = {
            product_id: productId,
            _token: csrf_token,
            quantity: quantity,
        };

        // Add variant ID to the request if available
        if (variantId) {
            data.variant_id = variantId;
        }

        // Disable the button and show loading state
        addToCartButton
            .data('loading', true)
            .prop('disabled', true)
            .addClass('btn-loading');

        // Send the request
        $.ajax({
            url: cartAddRoute,
            type: 'POST',
            data: data,
            complete: function() {
                // Re-enable the button and remove loading state
                addToCartButton
                    .data('loading', false)
                    .prop('disabled', false)
                    .removeClass('btn-loading');
            },
            success: function(response) {
                if (response.error) {
                    toastr.error(response.error);
                } else {
                    toastr.success(cartUpdateSuccessMessage);
                    updateCartDetails();
                    // Close the modal after successful addition
                    $('.product-quick-view-modal').hide();
                }
            },
            error: function(error) {
                console.error(error);
                toastr.error(__('scripts.cart_update_error'));
            }
        });
    }

    function updateCartCount() {
        const cartItems = $('#cart-items-list li').length;
        const cartCountElement = $('.cart-count');
        const cartButtons = $('.cart-checkout-btn a');

        if (cartItems === 0) {
            // إخفاء عداد السلة
            cartCountElement.hide();

            // تعطيل الأزرار وإضافة class للتنسيق
            cartButtons
                .addClass('disabled')
                .css({
                    'pointer-events': 'none',
                    'opacity': '0.7'
                })
                .attr('title', 'السلة فارغة');

        } else {
            // إظهار العداد وتحديث القيمة
            cartCountElement.show().text(cartItems);

            // تفعيل الأزرار وإزالة التنسيق
            cartButtons
                .removeClass('disabled')
                .css({
                    'pointer-events': 'auto',
                    'opacity': '1'
                })
                .removeAttr('title');
        }
    }
    function findVariantId(productId) {
        // البحث عن معرف المتغير في المودال أو الصفحة الرئيسية
        const modalVariant = $('.product-quick-view-modal .variant-id-holder').val();
        if (modalVariant) return modalVariant;

        const pageVariant = $(`[data-product-id="${productId}"].variant-id`).val();
        return pageVariant || null;
    }

    // تحديث تفاصيل السلة
    function updateCartDetails() {
        $.ajax({
            url: cartDetailsRoute,
            type: 'GET',
            success: function (response) {
                $('#cart-total-quantity').text(response.totalQuantity);
                $('#cart-total-price').text((response.totalPrice).toFixed(2) + ' ' + currency);

                var itemsList = $('#cart-items-list');
                itemsList.empty();

                for (var key in response.items) {
                    if (response.items.hasOwnProperty(key)) {
                        var item = response.items[key];
                        var variantInfo = '';

                        // Build variant information with better formatting
                        if (item.attributes.variant_details) {
                            variantInfo = '<div class="variant-info">';
                            const entries = Object.entries(item.attributes.variant_details);
                            entries.forEach(([optionName, details], index) => {
                                        variantInfo += `
                                <span class="variant-option">
                                    <span class="option-value">${details.value}</span>
                                    ${index < entries.length - 1 ? ',&nbsp;' : ''}
                                </span>
                                `;
                            });
                            variantInfo += '</div>';
                        }
                        var itemHTML = `
                        <li class="single-product-cart">
                            <div class="cart-img">
                                <a href="${item.attributes.url}">
                                    <img src="${storageUrl}/${item.attributes.image}" alt="${item.name}">
                                </a>
                            </div>
                            <div class="cart-title">
                                <h4><a href="${item.attributes.url}">${item.name}</a></h4>
                                ${variantInfo}
                                <span class="quantity-price">
                                    ${item.quantity} × <span class="price">${parseFloat(item.price).toFixed(2)} ${currency}</span>
                                </span>
                    `;

                        if (item.attributes.free_quantity > 0) {
                            itemHTML += `
                            <span class="free-quantity">
                                + <span class="free-quantity-number">${item.attributes.free_quantity}</span> ${freeItemText}
                            </span>
                        `;
                        }

                        itemHTML += `
        </div>
        <div class="cart-delete">
            <a href="javascript:void(0);"
               onclick="removeFromCart(event, this)"
               data-id="${item.id}"
               data-variant-id="${item.attributes.variant_id || null}"
               class="remove-item">
                <i class="pe-7s-trash icons"></i>
            </a>
        </div>
    </li>
`;

                        itemsList.append(itemHTML);
                    }
                }
                // إعادة ربط الأحداث
                bindQuantityEvents();

                // تحديث العداد
                updateCartCount();
            },
            error: function (error) {
                console.error('Error updating cart details:', error);
                toastr.error(cartDetailsErrorMessage);
            }
        });
    }
    // دالة ربط أحداث الكمية
    function bindQuantityEvents() {
        document.querySelectorAll('input[data-variant-id]').forEach(function (input) {
            input.removeEventListener('change', quantityChangeHandler);
            input.addEventListener('change', quantityChangeHandler);
        });
    }

    // معالج حدث تغيير الكمية
    function quantityChangeHandler() {
        var quantity = Math.max(1, parseInt(this.value) || 1);
        var price = parseFloat(this.getAttribute('data-price')) || 0;
        var productId = this.getAttribute('data-product-id');
        var variantId = this.getAttribute('data-variant-id');
        var total = price * quantity;

        // تحديث السعر الإجمالي
        var totalPriceElement = document.querySelector(`#total-price-${productId}-${variantId}`);
        if (totalPriceElement) {
            totalPriceElement.innerText = total.toFixed(2) + ' {{ __("shop-cart.currency") }}';
        }

        // استدعاء تحديث السلة
        updateQuantity(productId, quantity, variantId);
    }

    // --------------------------------------
    // تحديث كمية المنتج في السلة
    function updateCart(productId,quantity=1, variantId = null) {
        const data = {
            product_id: productId,
            quantity: quantity,
            _token: csrf_token
        };

        if (variantId) {
            data.variant_id = variantId;
        }

        $.ajax({
            url: cartUpdateRoute,
            type: 'POST',
            data: data,
            success: function (response) {
                if (response.error) {
                    toastr.error(response.error);
                } else {
                toastr.success(cartUpdateSuccessMessage);
                updateCartDetails();
                }
            },
            error: function (error) {
                console.error('Error updating cart:', error);
                toastr.error(cartUpdateErrorMessage);
            }
        });
    }

    // فانكشنز  صفحة الشوب كارت
    function updateQuantity(productId, quantity = 1, variantId = null) {
        const data = {
            product_id: productId,
            quantity: quantity,
            _token: csrf_token
        };

        if (variantId) {
            data.variant_id = variantId;
        }

        $.ajax({
            url: cartUpdateRoute,
            type: 'POST',
            data: data,
            success: function (response) {
                if (response.success) {
                    // تحديث تفاصيل السلة مباشرة بعد التحديث
                    updateCartDetails();

                    // تحديث الكمية المجانية إذا وجدت
                    if (response.free_quantity > 0) {
                        $(`#free_quantity${response.item_id}`).html(` + عدد <span>${response.free_quantity}</span> قطعة مجاني`);
                    } else {
                        $(`#free_quantity${response.item_id}`).text('');
                    }

                    // تحديث المجموع الكلي
                    updateGrandTotal();
                } else {
                    toastr.error(response.error || 'حدث خطأ أثناء تحديث الكمية');
                }
            },
            error: function (xhr, status, error) {
                console.error('Error updating quantity:', xhr.responseText);
                toastr.error('حدث خطأ أثناء تحديث الكمية');
            }
        });
    }


    function updateGrandTotal() {
        var grandTotal = 0;
        document.querySelectorAll('.product-total span').forEach(function (span) {
            grandTotal += parseFloat(span.innerText);
        });
        document.getElementById('grand-total').innerText = grandTotal + ' {{ __('shop-cart.currency') }}';
    }


    // حذف منتج من السلة
    // تخزين العناصر المستخدمة بشكل متكرر
    const SELECTORS = {
        CART_COUNTER: '.cart-counter',
        CART_TOTAL: '.cart-total'
    };

    function removeFromCart(event, element) {
        event.preventDefault();

        const $element = $(element);

        // التحقق من حالة العنصر باستخدام data attribute بدلاً من class
        if ($element.data('processing')) {
            return;
        }

        // تعيين حالة المعالجة
        $element
            .data('processing', true)
            .addClass('loading')
            .prop('disabled', true);

        // استخراج البيانات مرة واحدة
        const requestData = {
            product_id: $element.data('id'),
            variant_id: $element.data('variant-id'),
            _token: csrf_token
        };

        // إنشاء Promise للـ AJAX request
        const removeRequest = $.ajax({
            url: cartRemoveRoute,
            type: 'POST',
            data: requestData,
            timeout: 10000 // timeout بعد 10 ثواني
        });

        removeRequest
            .then(response => {
                if (response.success) {
                    // معالجة النجاح
                    handleSuccess(response);

                    // تشغيل animation للعنصر المحذوف إذا كان موجوداً في صفحة السلة
                    const $cartItem = $element.closest('.cart-item');
                    if ($cartItem.length) {
                        $cartItem.fadeOut(300, () => $cartItem.remove());
                    }
                } else {
                    throw new Error(response.message || 'حدث خطأ أثناء حذف المنتج');
                }
            })
            .catch(error => {
                // معالجة الخطأ
                console.error('Error removing item from cart:', error);
                toastr.error(error.message || cartRemoveErrorMessage, '', {
                    timeOut: 3000,
                    closeButton: true,
                    progressBar: true
                });
            })
            .finally(() => {
                // تنظيف حالة العنصر
                cleanupElement($element);
            });
    }

    // دوال مساعدة لتنظيم الكود
    function handleSuccess(response) {
        // عرض رسالة النجاح مع خيارات محسنة
        toastr.success(cartRemoveSuccessMessage, '');

        // تحديث تفاصيل السلة بشكل متزامن
        if (typeof updateCartDetails === 'function') {
            updateCartDetails();
        } else {
            // تحديث العناصر الفردية إذا كانت دالة updateCartDetails غير متوفرة
            updateCartUI(response);
        }
    }

    function updateCartUI(response) {
        // تحديث عداد السلة
        const $counter = $(SELECTORS.CART_COUNTER);
        if ($counter.length && response.cartCount !== undefined) {
            $counter.text(response.cartCount);
        }

        // تحديث إجمالي السلة
        const $total = $(SELECTORS.CART_TOTAL);
        if ($total.length && response.cartTotal !== undefined) {
            $total.fadeOut(150).text(response.cartTotal).fadeIn(150);
        }
    }

    function cleanupElement($element) {
        $element
            .removeData('processing')
            .removeClass('loading')
            .prop('disabled', false);
    }    // حذف منتج من صفحة الشوب كارت
    function removeItem(event, element) {
        event.preventDefault();

        // منع النقر المتكرر عن طريق التحقق من حالة العنصر
        const $element = $(element);
        if ($element.data('processing')) {
            return;
        }

        // وضع علامة أن العنصر قيد المعالجة
        $element.data('processing', true);

        // إضافة class للإشارة بصرياً أن العملية جارية
        $element.addClass('processing').prop('disabled', true);

        const elementId = $element.data('id');
        const $row = $element.closest('tr');

        // تخزين مرجع لـ tbody لتحسين الأداء
        const $tbody = $row.parent();

        $.ajax({
            url: '{{ route("cart.remove-item") }}',
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                id: elementId,
            },
            success: function (response) {
                if (response.success) {
                    // استخدام animation للحذف
                    $row.fadeOut(300, function() {
                        $(this).remove();

                        // التحقق من وجود منتجات
                        if ($tbody.children('tr').length === 0) {
                            const emptyMessage = $('<tr><td colspan="6" class="text-center">{{ __("shop-cart.no_products") }}</td></tr>');
                            $tbody.append(emptyMessage.hide().fadeIn(300));
                        }
                    });

                    // تحديث إجمالي السلة بشكل متزامن
                    if (typeof updateCartDetails === 'function') {
                        updateCartDetails();
                    }

                    // تحديث المجموع الكلي مع animation
                    const $grandTotal = $('#grand-total');
                    if ($grandTotal.length) {
                        $grandTotal
                            .fadeOut(150)
                            .text(response.cartTotal + ' {{ __("shop-cart.currency") }}')
                            .fadeIn(150);
                    }
                } else {
                    showError('حدث خطأ أثناء حذف المنتج');
                }
            },
            error: function (error) {
                console.error('Error:', error);
                showError('حدث خطأ أثناء حذف المنتج');
            },
            complete: function() {
                // إزالة علامة المعالجة وتمكين العنصر مرة أخرى
                $element
                    .removeData('processing')
                    .removeClass('processing')
                    .prop('disabled', false);
            }
        });
    }

    // دالة مساعدة لعرض رسائل الخطأ
    function showError(message) {
        const $errorAlert = $('<div>')
            .addClass('alert alert-danger alert-dismissible fade show')
            .html(`
            ${message}
            <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
        `);

        // إضافة التنبيه في أعلى الصفحة
        $('main').prepend($errorAlert);

        // إزالة التنبيه تلقائياً بعد 3 ثواني
        setTimeout(() => {
            $errorAlert.alert('close');
        }, 3000);
    }
    //------------------------ products filter  toolbar-----------

    document.addEventListener('DOMContentLoaded', function () {
        const toggleFilterBtn = document.getElementById('toggleFilter');
        const filterContent = document.getElementById('filterContent');

        toggleFilterBtn.addEventListener('click', function () {
            if (filterContent.style.display === 'none') {
                filterContent.style.display = 'block';
                toggleFilterBtn.innerHTML = '<i class="fa fa-filter mr-2"></i>اخفاء التصفية ';
            } else {
                filterContent.style.display = 'none';
                toggleFilterBtn.innerHTML = '<i class="fa fa-filter mr-2"></i>تصفية المنتجات';
            }
        });
    });

    // ------------------------------- Toastr options -------------------------------


    toastr.options = {
        "positionClass": "toast-top-left custom-toast-position" , // هنا نغير الموقع إلى أعلى اليسار
        "closeButton": true,
        "progressBar": true,
        "showDuration": "1000", // مدة عرض الرسالة
        "hideDuration": "1000", // مدة اختفاء الرسالة
        "timeOut": "1000", // مدة عرض الرسالة قبل الاختفاء (بالملي ثانية)
    };

    @if(Session::has('success'))
    toastr.success("{{ Session::get('success') }}");
    @endif

    @if(Session::has('error'))
    toastr.error("{{ Session::get('error') }}");

    @endif

</script>
@stack('scripts')
</body>

</html>
