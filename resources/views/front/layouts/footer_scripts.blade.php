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

<script>

    // عرض مودال تفاصيل البروداكت
    function showProductDetails(element) {
        var productId = $(element).data('id'); // احصل على معرف المنتج من خاصية الزر

// استدعاء AJAX للحصول على تفاصيل المنتج
        $.ajax({
            url: 'product/details/'+productId, // المسار للوصول إلى تفاصيل المنتج
            method: 'GET',
            success: function (response) {

                $('.product-quick-view-modal .product-name').text(response.name);
                $('.product-quick-view-modal .price').text (response.price);
                $('.product-quick-view-modal .product-desc').text(response.description);
                $('.product-quick-view-modal .thumb img').attr('src', response.image);

                $('.product-quick-view-modal').show();
            },
            error: function (error) {
                console.log('Error fetching product details:', error);
            }
        });
    }
</script>

</body>

</html>
