@extends('front.layouts.app')
@section('title','قائمة الأمنيات')
@section('content')

    <!--== Start Page Title Area ==-->
    <section class="page-title-area">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-12 m-auto">
                    <div class="page-title-content text-center">
                        <h2 class="title">{{ __('wishlist.page_title') }}</h2>
                        <div class="bread-crumbs">
                            <a href="{{ route('home.index') }}">{{ __('wishlist.home') }}</a>
                            <span class="breadcrumb-sep"> / </span>
                            <span class="active">{{ __('wishlist.wishlist') }}</span>
                        </div>
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
                        <h2 class="title">{{ __('wishlist.wishlist') }}</h2>
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
                                        <th class="width-thumbnail">{{ __('wishlist.image') }}</th>
                                        <th class="width-name">{{ __('wishlist.product') }}</th>
                                        <th class="width-price">{{ __('wishlist.price') }}</th>
                                        <th class="width-stock-status">{{ __('wishlist.stock_status') }}</th>
                                        <th class="width-wishlist-cart"></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @if($wishlists->isEmpty())
                                        <tr>
                                            <td colspan="6" style="text-align: center; color: red;">
                                                {{ __('wishlist.no_products') }}
                                            </td>
                                        </tr>
                                    @else
                                        @foreach($wishlists as $item)
                                            <tr id="wishlist-item-{{ $item->id }}">
                                                <td class="product-remove">
                                                    <a href="#" onclick="removeFromWishlist(event, {{ $item->id }})">×</a>
                                                </td>
                                                <td class="product-thumbnail">
                                                    <img src="{{ asset('storage').'/'. $item->product->images()->first()?->path }}" alt="{{ __('wishlist.image_alt') }}" style="max-width: 75px">
                                                </td>
                                                <td class="product-name">
                                                    <h5>
                                                        <a href="">{{ $item->product->name }}</a>
                                                    </h5>
                                                </td>
                                                <td class="product-price">
                                                    <span class="amount">{{ $item->product->price }} {{ __('wishlist.currency') }}</span>
                                                </td>
                                                <td class="stock-status">
                                                    @if($item->product->quantity > 0)
                                                        <span><i class="fa fa-check"></i> {{ __('wishlist.in_stock') }}</span>
                                                    @else
                                                        <span style="color: red"><i class="fa fa-times"></i> {{ __('wishlist.out_of_stock') }}</span>
                                                    @endif
                                                </td>
                                                <td class="wishlist-cart">
                                                    <a href="{{ route('cart.add', $item->product->id)}}" class="btn btn-lg"><strong>{{ __('wishlist.add_to_cart') }} &nbsp;<i class="ion-ios-cart"></i></strong></a>
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
                    $('#wishlist-item-' + itemId).remove();
                    toastr.success(response.message);
                },
                error: function (error) {
                    console.error(error);
                }
            });
        }
    </script>
@endpush
