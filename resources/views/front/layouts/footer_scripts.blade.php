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
<script src="{{asset('front/assets')}}/js/custom.js"></script>

{{--toastr--}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>


<script>




    $(document).ready(function() {
        // إظهار المودال والتراكب عند النقر على الرابط
        $('.action-quick-view').on('click', function(event) {
            event.preventDefault();
            $('.product-quick-view-modal').fadeIn(200); // إظهار المودال
            $('.canvas-overlay').fadeIn(200); // إظهار التراكب
        });

        // إخفاء المودال والتراكب عند النقر على زر الإغلاق
        $('.btn-close').on('click', function() {
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

        // استدعاء AJAX للحصول على تفاصيل المنتج
        $.ajax({
            url: 'product/details/'+productId, // المسار للوصول إلى تفاصيل المنتج
            method: 'GET',
            success: function (response) {
                $('.product-quick-view-modal .product-name').text(response.name);
                $('.product-quick-view-modal .price').text(response.price);
                $('.product-quick-view-modal .product-desc').text(response.description);

                categoriesContainer.empty();
                response.categories.forEach(function(cat){
                    categoriesContainer.append('&nbsp;<a href="#">'+cat['name']+'</a> &nbsp;&nbsp;  ');
                });

                sliderContainer.empty();
                response.images.forEach(function(image){
                    sliderContainer.append(`<div class="swiper-slide"><img src="{{asset('storage/')}}/${image.path}" alt="Product Image" /></div>`);
                });

                $('.product-quick-view-modal').show();
            },
            error: function (error) {
                console.log('Error fetching product details:', error);
            }
        });
    }

    // إضافة المنتجات للويش ليست
    function wishListAdd(event, element) {
        event.preventDefault();

        var productId = $(element).data('id'); // احصل على معرف المنتج من خاصية الزر

        $.ajax({
            url: 'wishlist/' + productId,
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if(response.message) {
                    toastr.success(response.message);
                }
                if(response.err) {
                    toastr.error(response.err);
                }
            },
            error: function(xhr) {
                if (xhr.status === 401) {
                    toastr.warning('{{ __('scripts.login_required') }}');
                } else {
                    toastr.error('{{ __('scripts.error') }}', 'خطأ');
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

    // ------------------------------ Shopping Cart--------------------------------

    // إضافة منتج إلى السلة
    function addToCart(event, productId) {
        event.preventDefault();

        $.ajax({
            url: '{{ route("cart.add") }}',
            type: 'POST',
            data: {
                product_id: productId,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                toastr.success('{{ __('scripts.cart_update_success') }}');
                // تحديث تفاصيل السلة بعد إضافة المنتج
                updateCartDetails();
            },
            error: function(error) {
                toastr.error('{{ __('scripts.cart_update_error') }}');
            }
        });
    }

    function updateCartDetails() {
        $.ajax({
            url: '{{ route("cart.details") }}',
            type: 'GET',
            success: function(response) {
                // تحديث عدد المنتجات والإجمالي في الواجهة
                $('#cart-total-quantity').text(response.totalQuantity);
                $('#cart-total-price').text(response.totalPrice.toFixed(2) + ' {{ __('scripts.currency_symbol') }} ');

                // إذا كانت items كائنًا، قم بالتكرار على خصائصه
                var itemsList = $('#cart-items-list');
                itemsList.empty(); // تنظيف القائمة الحالية

                for (var key in response.items) {
                    if (response.items.hasOwnProperty(key)) {
                        var item = response.items[key];
                        var url = item.attributes.url;
                        var image = item.attributes.image;
                        itemsList.append(`
                        <li class="single-product-cart">
                            <div class="cart-img">
                                <a href="${url}"><img src="{{asset('storage')}}/${image}" alt=""></a>
                            </div>
                            <div class="cart-title">
                                <h4><a href="${url}">${item.name}</a></h4>
                                <span>${item.quantity} × <span class="price">${item.price.toFixed(2)}  {{ __('scripts.currency_symbol') }} </span></span>
                            </div>
                            <div class="cart-delete">
                                <a href="javascript:void(0);" data-id="${item.id}" onclick="removeFromCart(${item.id})" class="remove-item"><i class="pe-7s-trash icons"></i></a>
                            </div>
                        </li>
                    `);
                    }
                }
            },
            error: function(error) {
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
            success: function(response) {
                toastr.success('{{ __('scripts.cart_update_success') }}');
                getCartDetails();
            },
            error: function(error) {
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
            success: function(response) {
                toastr.success('{{ __('scripts.cart_remove_success') }}');
                updateCartDetails();
            },
            error: function(error) {
                toastr.error('{{ __('scripts.cart_remove_error') }}');
            }
        });
    }


    // ------------------------------- Toastr options -------------------------------


    toastr.options = {
        "positionClass": "toast-top-left", // هنا نغير الموقع إلى أعلى اليسار
        "closeButton": true,
        "progressBar": true,
        "showDuration": "2000", // مدة عرض الرسالة
        "hideDuration": "1000", // مدة اختفاء الرسالة
        "timeOut": "4000", // مدة عرض الرسالة قبل الاختفاء (بالملي ثانية)
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
