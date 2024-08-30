@extends('front.layouts.app')
@section('content')

    <!--== Start Page Title Area ==-->
    <section class="page-title-area">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-12 m-auto">
                    <div class="page-title-content text-center">
                        <h2 class="title">Wishlist</h2>
                        <div class="bread-crumbs"><a href="{{route('home.index')}}"> الرئيسية </a><span
                                class="breadcrumb-sep"> / </span><span class="active"> قائمة الأمنيات</span></div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--== End Page Title Area ==-->

    <!--== Start Wishlist Area Wrapper ==-->
    <section class="product-area wishlist-page-area">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 m-auto">
                    <div class="section-title text-center">
                        <h2 class="title">Wishlist</h2>
                    </div>
                </div>
            </div>
            <div class="row" style="direction: rtl">
                <div class="col-12">
                    <form action="#">
                        <div class="wishlist-table-content">
                            <div class="table-content table-responsive">
                                <table class="text-center">
                                    <thead>
                                    <tr>
                                        <th class="width-remove"></th>
                                        <th class="width-thumbnail">صورة المنتج</th>
                                        <th class="width-name">المنتج</th>
                                        <th class="width-price">سعر المنتج</th>
                                        <th class="width-stock-status">تواجد المنتج</th>
                                        <th class="width-wishlist-cart"></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @if($wishlists->isEmpty())
                                        <!-- صف كامل في حالة عدم وجود أي عناصر -->
                                        <tr>
                                            <td colspan="6" style="text-align: center; color: red;">
                                                لا توجد منتجات في قائمة الأمنيات.
                                            </td>
                                        </tr>
                                    @else
                                        @foreach($wishlists as $item)

                                            <tr id="wishlist-item-{{ $item->id }}">
                                                <td class="product-remove">
                                                    <a href="#"
                                                       onclick="removeFromWishlist(event, {{ $item->id }})">×</a>
                                                </td>
                                                <td class="product-thumbnail">
                                                    {{--                                                <a href="{{ route('product.show', $item->product->id) }}">--}}
                                                    <img
                                                        src="{{asset('storage').'/'. $item->product->images()->first()->path }}"
                                                        alt="Image" style="max-width: 75px">
                                                    </a>
                                                </td>
                                                <td class="product-name">
                                                    <h5>
                                                        <a href="">{{ $item->product->name }}</a>
                                                    </h5>
                                                </td>
                                                <td class="product-price">
                                                    <span class="amount">{{ $item->product->price }} ج</span>
                                                </td>
                                                <td class="stock-status">
                                                    @if($item->product->quantity>0)
                                                        <span><i class="fa fa-check"></i> موجود بالستوك</span>
                                                    @else
                                                        <span style="color: red"><i
                                                                class="fa fa-times"></i> غير متوفر</span>
                                                    @endif
                                                </td>
                                                <td class="wishlist-cart">
                                                    <a href="{{ route('cart.add', $item->product->id)}}" class="btn btn-lg"><strong>أضف للسلة &nbsp;<i class="ion-ios-cart"></i></strong></a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                    </tbody>
                                </table>

                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
    <!--== End Wishlist Area Wrapper ==-->
@endsection

@push('scripts')
    <script>

        function removeFromWishlist(event, itemId) {
            event.preventDefault();
            $.ajax({
                url: 'wishlist/' + itemId,
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // تضمين CSRF Token في الطلب
                },
                success: function (response) {
                    // تحديث الصفحة أو إزالة العنصر من الجدول
                    $('#wishlist-item-' + itemId).remove();
                    toastr.success(response.message);


                },
                error: function (error) {
                    // التعامل مع الخطأ
                    console.error(error);
                }
            });
        }
    </script>

@endpush
