<!--=======================Javascript============================-->

<!--=== Modernizr Min Js ===-->
<script src="{{asset('front/assets')}}/js/modernizr.js"></script>
<!--=== jQuery Min Js ===-->
<script src="{{asset('front/assets')}}/js/jquery-main.js"></script>
<!--=== jQuery Migration Min Js ===-->
<script src="{{asset('front/assets')}}/js/jquery-migrate.js"></script>
<!--=== Popper Min Js ===-->
<script src="{{asset('front/assets')}}/js/popper.min.js"></script>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>


<script>

    $(document).ready(function () {

        $('.action-quick-view').on('click', function (event) {
            event.preventDefault();

            // var button = $(this);

            // تحديث محتوى المودال بالبيانات الجديدة
            // openProductModal(button);

            // إظهار المودال والتراكب
            $('.product-quick-view-modal').fadeIn(200); // إظهار المودال
            $('.canvas-overlay').fadeIn(200); // إظهار التراكب
        });

        // إخفاء المودال والتراكب عند النقر على زر الإغلاق
        $('.btn-close').on('click', function () {
            $('.product-quick-view-modal').fadeOut(); // إخفاء المودال
            $('.canvas-overlay').fadeOut(); // إخفاء التراكب
        });


        updateCartDetails();
    });

    // عرض مودال تفاصيل البروداكت
    function showProductDetails(element) {
        var productId = $(element).data('id'); // احصل على معرف المنتج من خاصية الزر
        var categoriesContainer = $('.product-quick-view-modal .product-categories');
        var sliderContainer = $('.product-images-slider');
        var url = "{{route('product.details',':productId')}} ";
        var myUrl = url.replace(':productId', productId);

        // استدعاء AJAX للحصول على تفاصيل المنتج
        $.ajax({
            url: myUrl, // المسار للوصول إلى تفاصيل المنتج
            method: 'GET',
            success: function (response) {

                $('.product-quick-view-modal .product-name').text(response.name);

                let productPrice = response.price;
                let discountedPrice = response.discounted_price;
                if (productPrice === discountedPrice) {
                    $('.product-quick-view-modal .prices')
                        .html(`
                                <span class="price" style="color: #e74c3c;">
                                ${discountedPrice} {{ __('home.currency') }}
                        </span>
`);
                } else {
                    $('.product-quick-view-modal .prices')
                        .html(`
                                 <span class="price-old" style="text-decoration: line-through; color: #999; font-size:18px; ">
                                ${productPrice} {{ __('home.currency') }}
                        </span>
                        <span class="price" style="color: #e74c3c;">
${discountedPrice} {{ __('home.currency') }}
                        </span>
`);
                }
                // إدخال القيم في الـ HTML
                $('.product-quick-view-modal .product-desc').html(response.description);

                categoriesContainer.empty();
                response.categories.forEach(function (cat) {
                    categoriesContainer.append('&nbsp;<a href="#">' + cat['name'] + '</a> &nbsp;&nbsp;  ');
                });

                sliderContainer.empty();
                if (response.images && response.images.length > 0) {
                    const image = response.images[0]; // نأخذ الصورة الأولى فقط
                    sliderContainer.append(`<div class="swiper-slide"><img src="<?php echo e(asset('storage/')); ?>/${image.path}" alt="Product Image" /></div>`);
                } else {
                    // إذا لم تكن هناك صور، يمكنك إضافة صورة افتراضية أو رسالة
                    sliderContainer.append(`<div class="swiper-slide"><p>No image available</p></div>`);
                }

                // تحديث زر الإضافة للسلة بالمنتج المحدد
                $('.quick-product-action button').attr('onclick', `addToCart(event, ${productId}, document.getElementById('quantity_${productId}').value)`);

                //زر الويش ليست
                $('.quick-product-action  .btn-wishlist').attr('onclick', `wishListAdd(event,this,${productId})`);

                // تحديث حقل الكمية داخل المودال مع id جديد للمنتج الحالي
                $('.pro-qty input').attr('id', `quantity_${productId}`);
                $('.pro-qty input').val(1);

                $('.product-quick-view-modal').show();
            },
            error: function (error) {
                console.log('Error fetching product details:', error);
            }
        });
    }

    // إضافة المنتجات للويش ليست
    function wishListAdd(event, element, id = null) {
        event.preventDefault();
        if (id) {

            var url = "{{route('wishlist.store',':productId')}}".replace(':productId', id);
        } else {

            var productId = $(element).data('id'); // احصل على معرف المنتج من خاصية الزر
            var url = "{{route('wishlist.store',':productId')}}".replace(':productId', productId);

        }


        $.ajax({
            url: url,
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function (response) {
                if (response.message) {
                    toastr.success(response.message);
                    console.log(productId)

                }
                if (response.err) {
                    toastr.error(response.err);
                }
            },
            error: function (xhr) {
                console.log(productId)
                if (xhr.status === 401) {
                    toastr.warning('{{ __('scripts.login_required') }}');
                } else {
                    toastr.error('{{ __('scripts.error') }}', 'خطأ');
                    console.log(xhr
                    )
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

    // إضافة منتج إلى السلة
    function addToCart(event, productId, quantity = 1) {
        event.preventDefault();

        quantity = parseInt(quantity);
        if (isNaN(quantity) || quantity < 1) {
            quantity = 1;
        }

        $.ajax({
            url: '{{ route("cart.add") }}',
            type: 'POST',
            data: {
                product_id: productId,
                _token: '{{ csrf_token() }}',
                quantity: quantity,
            },
            success: function (response) {
                toastr.success('{{ __('scripts.cart_update_success') }}');
                // تحديث تفاصيل السلة بعد إضافة المنتج
                updateCartDetails();
            },
            error: function (error) {
                console.log(error)
                toastr.error('{{ __('scripts.cart_update_error') }}');
            }
        });
    }

    function updateCartDetails() {
        $.ajax({
            url: '{{ route("cart.details") }}',
            type: 'GET',
            success: function (response) {
                console.log(response.items)
                // تحديث عدد المنتجات والإجمالي في الواجهة
                $('#cart-total-quantity').text(response.totalQuantity);
                $('#cart-total-price').text((response.totalPrice).toFixed(2) + ' {{ __('scripts.currency_symbol') }} ');

                // إذا كانت items كائنًا، قم بالتكرار على خصائصه
                var itemsList = $('#cart-items-list');
                itemsList.empty(); // تنظيف القائمة الحالية

                for (var key in response.items) {
                    if (response.items.hasOwnProperty(key)) {
                        var item = response.items[key];
                        var url = item.attributes.url;
                        var image = item.attributes.image;
                        var freeQuantity = item.attributes.free_quantity;

                    // إنشاء النص الأساسي الذي سيتم إدراجه
                        var itemHTML = `
                                <li class="single-product-cart">
                                    <div class="cart-img">
                                        <a href="${url}"><img src="{{asset('storage')}}/${image}" alt=""></a>
                                    </div>
                                    <div class="cart-title">
                                        <h4><a href="${url}">${item.name}</a></h4>
                                        <span>${item.quantity} × <span class="price">${parseFloat(item.price).toFixed(2)}  {{ __('scripts.currency_symbol') }} </span></span>
                                  `;

                        // التحقق إذا كانت freeQuantity أكبر من صفر
                        if (freeQuantity > 0) {
                            itemHTML += `<span class='free-quantity'> + <span class="free-quantity-number">${freeQuantity}</span> قطعة مجاني</span>`;
                        }

                            // إكمال النص وإضافة أي عناصر أخرى
                        itemHTML += `
                                </div>
                                <div class="cart-delete">
                                    <a href="javascript:void(0);" data-id="${item.id}" onclick="removeFromCart(${item.id})" class="remove-item"><i class="pe-7s-trash icons"></i></a>
                                </div>
                            </li>
                          `;

                        // إضافة النص الكامل إلى القائمة
                        itemsList.append(itemHTML);
                    }
                }
            },
            error: function (error) {
                toastr.error('{{ __('scripts.cart_details_error') }}');
            }
        });
    }

    // تحديث كمية المنتج في السلة
    function updateCart(productId, quantity) {
        $.ajax({
            url: '{{ route("cart.update") }}',
            type: 'POST',
            data: {
                product_id: productId,
                quantity: quantity,
                _token: '{{ csrf_token() }}'
            },
            success: function (response) {
                toastr.success('{{ __('scripts.cart_update_success') }}');
                getCartDetails();
            },
            error: function (error) {
                toastr.error('{{ __('scripts.cart_update_error') }}');
            }
        });
    }

    // حذف منتج من السلة
    function removeFromCart(productId) {
        $.ajax({
            url: '{{ route("cart.remove") }}',
            type: 'POST',
            data: {
                product_id: productId,
                _token: '{{ csrf_token() }}'
            },
            success: function (response) {
                toastr.success('{{ __('scripts.cart_remove_success') }}');
                updateCartDetails();
            },
            error: function (error) {
                toastr.error('{{ __('scripts.cart_remove_error') }}');
            }
        });
    }


    // فانكشنز  صفحة الشوب كارت
    document.querySelectorAll('.quantity-input').forEach(function (input) {
        input.addEventListener('input', function () {
            var quantity = this.value;
            var price = this.getAttribute('data-price');
            var id = this.getAttribute('data-id');
            var total = price * quantity;
            document.getElementById('total-price-' + id).innerText = total + ' {{ __('shop-cart.currency') }}';

            // حساب وإظهار الإجمالي الكلي الجديد
            updateQuantity(id, quantity)
            updateGrandTotal();
        });
    });

    function updateGrandTotal() {
        var grandTotal = 0;
        document.querySelectorAll('.product-total span').forEach(function (span) {
            grandTotal += parseFloat(span.innerText);
        });
        document.getElementById('grand-total').innerText = grandTotal + ' {{ __('shop-cart.currency') }}';
    }

    // تحديث الكمية في صفحة الشوب كارت بعد تغييرها بالاجاكس
    function updateQuantity(productId, quantity) {
        $.ajax({
            url: `{{route('cart.update')}}`,
            type: 'POST',
            data: {
                quantity: quantity,
                product_id: productId,
                _token: '{{ csrf_token() }}'
            },
            success: function (data) {
                if (data.success) {
                    console.log('Quantity updated successfully');
                    updateCartDetails();
                    if (data.free_quantity > 0) {
                        $('#free_quantity' + productId).html(' + عدد   <span>' + data.free_quantity + '</span>  قطعة مجاني ');
                    } else {
                        $('#free_quantity' + productId).text('');
                    }

                }
            },
            error: function (xhr, status, error) {
                console.error('Error updating quantity:', error);
            }
        });
    }

    // حذف منتج من صفحة الشوب كارت
    function removeItem(event, element) {
        event.preventDefault();
        var productId = $(element).data('id');

        $.ajax({
            url: '{{ route("cart.remove") }}',
            type: 'POST',
            data: {
                product_id: productId,
                _token: '{{ csrf_token() }}'
            },
            success: function (response) {
                $(element).closest('tr').remove();
                updateCartDetails();
                updateGrandTotal();
            },
            error: function (error) {
                console.log('Failed to remove product from cart.');
            }
        });
    }

    // ------------------------------ End Shopping Cart--------------------------------


    //------------------------ products filter toolbar-----------

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
        "positionClass": "toast-top-left", // هنا نغير الموقع إلى أعلى اليسار
        "closeButton": true,
        "progressBar": true,
        "showDuration": "2000", // مدة عرض الرسالة
        "hideDuration": "2000", // مدة اختفاء الرسالة
        "timeOut": "1500", // مدة عرض الرسالة قبل الاختفاء (بالملي ثانية)
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
