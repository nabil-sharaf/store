@extends('front.layouts.app')
@section('title','تفاصيل الطلب')
@section('content')
    <!--== Start Page Title Area ==-->
    <section class="page-title-area">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-12 m-auto">
                    <div class="page-title-content text-center">
                        <h2 class="title">تفاصيل الطلب</h2>
                        <div class="bread-crumbs">
                            <a href="{{ route('home.index') }}">{{ __('wishlist.home') }}</a>
                            <span class="breadcrumb-sep"> / </span>
                            <span class="active">تفاصيل الطلب</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--== End Page Title Area ==-->
    <div class="myaccount-content">
        <h3>{{ __('profile.orders') }}</h3>
        <div class="myaccount-table table-responsive text-right">
            <div class="row">
                <div class="col-md-6">
                    <p class="customerName"><strong>المستخدم:</strong> {{ $order?->user?->name ?? 'Guest' }}</p>
                    <p><strong>اسم العميل:</strong> {{ $address->full_name ?? null}}</p>
                    <p><strong>رقم التليفون:</strong> {{ $address->phone ?? null }}</p>
                    <p><strong>العنوان:</strong> {{ $address->address ?? null }}</p>
                    <p><strong>المدينة :</strong> {{ $address->city ?? null }}
                        &nbsp;-<strong>&nbsp;المحافظة:</strong> {{ $address->state ?? null }}</p>
                    <p><strong>إجمالي الطلب:</strong> {{ $order->total_after_discount }} جنيه</p>
                    <p class="status-now"><strong>حالة الطلب:</strong> {{ ucfirst($order->status->name) }}</p>
                </div>
                <div class="col-md-6 status-now">

                    @if($order->status->id == 3)
                        <div class="alert alert-success p-1">
                            الطلب تم تسليمه بنجاح ✅
                        </div>
                    @elseif($order->status->id == 4)
                        <div class="alert alert-danger p-1">
                            الطلب ملغي ❌
                        </div>
                    @endif
                </div>
            </div>
            <hr>
            <h4>المنتجات في هذا الطلب:</h4>
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th>المنتج</th>
                    <th>كود المنتج</th>
                    <th>الكمية</th>
                    <th>السعر</th>
                    <th>الإجمالي</th>
                </tr>
                </thead>
                <tbody>
                @foreach($order->orderDetails as $detail)

                    @php
                       $options =  $detail->variant?->optionValues->map(function($value){
                           return $value->value;
                       })->implode(',') ??null;
                        @endphp

                    <tr>
                        <td>
                            {{
                            $detail->variant
                            ?$detail->product->name.($options? ': ('.$options.' )' :'')
                            :$detail->product->name
                             }}
                        </td>
                        <td>
                            {{
                            $detail->variant
                                ?$detail->variant->sku_code
                                :$detail->product->sku_code
                             }}
                        </td>
                        @if($detail->free_quantity > 0)
                            <td>{{ $detail->product_quantity }} + {{$detail->free_quantity .' هدية '}}</td>
                        @else
                            <td>{{ $detail->product_quantity }}</td>
                        @endif
                        <td>{{ $detail->price }} ج</td>
                        <td>{{ $detail->product_quantity * $detail->price }} ج</td>
                    </tr>
                @endforeach

                @if($order->user_id)
                    @if(($order->vip_discount + $order->promo_discount) > 0)
                        <tr class="">
                            <td colspan="4" class="text-left font-weight-bold">اجمالي سعر المنتجات</td>
                            <td class="table-active">{{ $order->total_price }} ج</td>
                        </tr>
                        <tr>
                            <td colspan="1" class="text-left font-weight-bold">قيمة الخصم</td>
                            <td colspan="1">خصم
                                كوبون: {{$order->promo_discount > 0 ? $order->promo_discount.'  ج ' : ' --- '}}</td>
                            <td colspan="1">خصم
                                (vip): {{$order->vip_discount > 0 ? $order->vip_discount.'  ج ' : ' --- '}}</td>
                            <td>{{ $order->vip_discount + $order->promo_discount }} ج</td>
                        </tr>
                    @endif
                @endif
                <tr>
                    <td colspan="4" class="text-left font-weight-bold">
                        تكاليف الشحن
                    </td>
                    <td class="font-weight-bold">{{$order->shipping_cost }} ج</td>
                </tr>
                <tr class="table-info">
                    <td colspan="4" class="text-left font-weight-bold">
                        {{ ($order->vip_discount > 0 || $order->promo_discount > 0) ? 'السعر الإجمالي للاوردر' : 'إجمالي الطلب' }}
                    </td>
                    <td class="font-weight-bold">{{ $order->final_total }} ج</td>
                </tr>
                </tbody>
            </table>
            <div class="card-footer">
                <a href="{{ route('profile.index') }}" class="btn btn-secondary">العودة للصفحة السابقة</a>

                @if($order->status->id == 1)
                    <a href="{{ route('order.edit',$order->id) }}" class="btn btn-primary"> <i
                            class="fa fa-edit">&nbsp;</i> تعديل الطلب </a>
                @endif
            </div>
        </div>
    </div>

@endsection

@Push('styles')
    <style>
        .myaccount-content {
            direction: rtl;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .myaccount-table {
            margin-top: 20px;
        }

        .customerName, .status-now {
            font-size: 18px;
            margin-bottom: 10px;
        }

        .table th, .table td {
            text-align: right;
        }

        @media (max-width: 768px) {
            .myaccount-content {
                padding: 10px;
            }

            .myaccount-table {
                font-size: 14px;
            }

            .btn {
                width: 100%;
                margin-bottom: 10px;
            }
        }
    </style>

@endpush
@push('scripts')
    <script>
    </script>
@endpush
