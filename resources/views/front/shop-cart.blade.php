@extends('front.layouts.app')
@section('title','سلة المشتريات')
@section('content')
    <!--== Start Page Title Area ==-->
    <section class="page-title-area">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-12 m-auto">
                    <div class="page-title-content text-center">
                        <h2 class="title">{{ __('shop-cart.page_title') }}</h2>
                        <div class="bread-crumbs">
                            <a href="{{ route('home.index') }}">{{ __('shop-cart.home') }}</a><span
                                class="breadcrumb-sep"> / </span><span class="active">{{ __('shop-cart.cart') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--== End Page Title Area ==-->

    <!--== Start Cart Area Wrapper ==-->
    <section class="product-area cart-page-area">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 m-auto">
                    <div class="section-title text-center">
                        <h2 class="title">{{ __('shop-cart.cart_details') }}</h2>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="cart-table-wrap" style="direction: rtl">
                        <div class="cart-table table-responsive">
                            <table>
                                <thead>
                                <tr>
                                    <th class="width-thumbnail"></th>
                                    <th class="width-name">{{ __('shop-cart.product') }}</th>
                                    <th class="width-price">{{ __('shop-cart.product_price') }}</th>
                                    <th class="width-quantity">{{ __('shop-cart.quantity') }}</th>
                                    <th class="width-subtotal">{{ __('shop-cart.subtotal') }}</th>
                                    <th class="width-remove"></th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($items as $item)
                                    <tr>
                                        <td class="product-thumbnail">
                                            <a href="{{ $item['attributes']->url }}"><img
                                                    src="{{ asset('storage/' . $item['attributes']->image) }}" alt="{{ __('shop-cart.image_alt') }}"></a>
                                        </td>
                                        <td class="product-name">
                                            <h5>
                                                <a href="{{ $item['attributes']->url }}">
                                                    {{ $item->name }}
                                                </a>
                                            </h5>
                                        </td>
                                        <td class="product-price"><span class="amount">{{ $item->price }} {{ __('shop-cart.currency') }}</span></td>
                                        <td>
                                            <div class="product-details-quality">
                                                <input type="number" class="input-text qty text quantity-input"
                                                       data-price="{{ $item->price }}" data-id="{{ $item->id }}"
                                                       step="1" min="1" max="100" name="quantity"
                                                       value="{{ $item->quantity }}" title="{{ __('shop-cart.qty_title') }}" placeholder="">
                                                <p class="free_quantity" id="free_quantity{{$item->id}}">
                                                    @if($item['attributes']->free_quantity > 0)
                                                        + عدد  <span>  {{$item['attributes']->free_quantity}}</span> قطعة مجاني
                                                    @endif
                                                </p>
                                            </div>
                                        </td>
                                        <td class="product-total">
                                            <span id="total-price-{{ $item->id }}">{{ $item->price * $item->quantity }} {{ __('shop-cart.currency') }}</span>
                                        </td>
                                        <td class="product-remove">
                                            <a href="#" class="remove-item" data-id="{{ $item->id }}" onclick="removeItem(event,this)"><i class="ion-ios-trash-outline"></i></a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">{{ __('shop-cart.no_products') }}</td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- إضافة القسم الخاص بإجمالي السعر الكلي -->
                        @if($items->isNotEmpty())
                        <div class="cart-total-summary">
                            <h3>{{ __('shop-cart.grand_total_title') }}:</h3>
                            <span id="grand-total">{{ $totalPrice }} {{ __('shop-cart.currency') }}</span>
                        </div>
                        @endif
                    </div>
                    @if($items->isNotEmpty())
                        <div class="grand-total-wrap">
                            <div class="grand-total-btn">
                                @if(session()->has('editing_order_id'))
                                    <a id = 'update-button-id' class="btn btn-link" href="{{route('checkout.indexEdit',session()->get('editing_order_id'))}}">تعديل الأوردر</a>
                                @else
                                <a class="btn btn-link" href="{{route('checkout.index',session()->get('editing_order_id'))}}">{{ __('shop-cart.proceed_checkout') }}</a>
                                @endif
                            </div>
                        </div>
                    @endif
                    <div class="cart-shiping-update-wrapper">
                        <div class="cart-shiping-btn continure-btn">
                            <a class="btn btn-link" href="javascript:void(0);" onclick="history.back();"><i
                                    class="ion-ios-arrow-left"></i> {{ __('shop-cart.go_back') }}</a>
                        </div>
                        <div class="cart-shiping-btn update-btn">
                            <a class="btn btn-link" href="{{ route('home.index') }}"><i class="ion-ios-reload"></i> {{ __('shop-cart.home') }}</a>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </section>
    <!--== End Cart Area Wrapper ==-->
@endsection

@push('styles')
    <style>
        .cart-total-summary {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px;
            margin-top: 20px;
            border-top: 2px solid #ddd;
            font-size: 18px;
            font-weight: bold;
            background-color: #f8f9fa;
        }

        .cart-total-summary h3 {
            margin: 0;
            color: #333;
        }

        .cart-total-summary span {
            color: #d9534f;
            margin-left: 30px;
        }

        .free_quantity{
            font-size: 13px;
            text-align: right;
            color: green;
            margin-bottom: -15px;

        }
        .free_quantity span {
            color: #d9534f;
            font-weight: bold;
            font-size: 14px;
        }
    </style>
@endpush

@push('scripts')

    <script>


        // مسح بيانات السلة في حالة مغادرة صفحة تعديل الاوردر
        let isUpdating = false; // متغير لتتبع حالة التحديث
        let isEditingOrder = {{ $order?->id ? 'true' : 'false' }}; // تحقق إذا كان هناك طلب يتم تعديله

        // إضافة حدث على زر التحديث
        $('#update-button-id').on('click', function() {
            isUpdating = true; // إذا تم الضغط على زر التحديث
        });

    </script>
@endpush
