@extends('admin.layouts.app')
@section('page-title')
    تفاصيل الطلب
@endsection


@section('content')
    <div class="card">
        <div
            class="card-header @if($order->status->id == 3) bg-success pb-0 @elseif($order->status->id == 4) pb-0 bg-danger @endif">
            <h3 class="card-title pb-0 float-left">تفاصيل الطلب رقم #{{ $order->id }}</h3>
            @if($order->status->id == 3)
                <span class="badge badge-light float-right mr-4">   &nbsp;الطلب مكتمل &nbsp;✅</span>
            @endif
            @if($order->status->id == 4)
                <span class="badge badge-light float-right mr-4">   &nbsp;الطلب ملغي &nbsp;❌</span>
            @endif
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p class="customerName"><strong>المستخدم:</strong> {{ $order?->user?->name ?? 'Guest' }}</p>
                    <p><strong>اسم العميل:</strong> {{ $address->full_name ?? null}}</p>
                    <p><strong>رقم التليفون:</strong> {{ $address->phone ?? null }}</p>
                    <p><strong>العنوان:</strong> {{ $address->address ?? null }}</p>
                    <p><strong>المدينة :</strong> {{ $address->city ?? null }} &nbsp;-<strong> &nbsp;المحافظة
                            :</strong> {{ $address->state ?? null }} </p>
                    <p><strong> إجمالي الطلب :</strong> {{ $order->total_after_discount }}
                        ج {{$order->shipping_cost > 0 ?  ' + '.$order->shipping_cost.'ج  شحن' : ''}} </p>
                    <p class="status-now"><strong>حالة الطلب :</strong> {{ ucfirst($order->status->name) }}</p>
                </div>
                <div class="col-md-6 status-now">
                    @if($order->status->id != 3 && $order->status->id != 4)
                        <form id="statusForm" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="form-group" id="statusGroup">
                                <label for="status">تغيير حالة الطلب:</label>
                                <select name="status" id="status" class="select2 form-control" style="width: 60%">
                                    <option id="processing" value="1" {{ $order->status->id == 1 ? 'selected' : '' }}>
                                        جاري المعالجة
                                    </option>
                                    <option value="2" {{ $order->status->id == 2 ? 'selected' : '' }}>جاري الشحن
                                    </option>
                                    <option value="3" {{ $order->status->id == 3 ? 'selected' : '' }}>تم التسليم
                                    </option>
                                    <option value="4" {{ $order->status->id == 4 ? 'selected' : '' }}>ملغي</option>
                                </select>
                            </div>
                        </form>
                    @elseif($order->status->id == 3)
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
                    <tr>
                        <td>
                            @if($detail->variant && $detail->variant->optionValues->isNotEmpty())
                                {{$detail->product->name .' : '}}
                                @foreach($detail->variant->optionValues as $value)
                                    {{ $value->getTranslation('value', 'ar') }}{{ !$loop->last ? ',' : '' }}
                                @endforeach
                            @else
                                {{ $detail->product->name }}
                            @endif
                        </td>
                        <td>
                            @if($detail->variant)
                                {{$detail->variant->sku_code}}
                            @else
                            {{$detail->product->sku_code}}
                            @endif
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
                            <td colspan="2" class="text-left font-weight-bold">قيمة الخصم</td>
                            <td colspan="1">خصم
                                كوبون: {{$order->promo_discount > 0 ? $order->promo_discount.'  ج ' : ' --- '}}</td>
                            <td colspan="1">
                                خصم
                                (vip): {{$order->vip_discount > 0 ? $order->vip_discount.'  ج ' : ' --- '}}</td>
                            <td>{{ $order->vip_discount + $order->promo_discount }} ج</td>
                        </tr>
                    @endif
                @endif
                <tr>
                    <td colspan="4" class="text-left font-weight-bold">
                        تكاليف الشحن
                    </td>
                    <td class="font-weight-bold">{{$order->shipping_cost > 0 ? $order->shipping_cost.' ج ' :'لا يوجد' }}</td>
                </tr>
                <tr class="table-info">
                    <td colspan="4" class="text-left font-weight-bold">
                        {{ ($order->vip_discount > 0 || $order->promo_discount > 0) ? 'السعر الإجمالي للاوردر' : 'إجمالي الطلب' }}
                    </td>
                    <td class="font-weight-bold">{{ $order->final_total }} ج</td>
                </tr>
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">رجوع إلى قائمة الطلبات</a>
            <button onclick="window.print()" class="btn btn-outline-primary float-right">
                طباعة <i class="fas fa-print"></i>
            </button>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        $(document).ready(function () {
            $('#status').on('change', function () {
                var formData = $('#statusForm').serialize();
                var url = '{{ route("admin.orders.updatestatus", $order->id) }}';
                let status = $('#status').val();

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: formData,
                    success: function (response) {
                        toastr.success('تم تحديث الحالة بنجاح!');
                        if (status == 2) {
                            $('#processing').remove();
                        }
                        if (status == 3) {
                            $('#statusForm').replaceWith('<div class="alert alert-success p-1">تم تسليم الاوردر ✅</div>')
                        }
                        if (status == 4) {
                            $('#statusForm').replaceWith('<div class="alert alert-danger p-1"> تم الغاء الطلب ❌</div>')
                        }

                    },
                    error: function (xhr, status, error) {
                        toastr.error('حدث خطأ أثناء تحديث الحالة.');
                    }
                });
            });
        });
    </script>
@endpush
@push('styles')
    <style>
        @media print {
            .btn, .card-footer, #statusGroup, title, .customerName, .status-now {
                display: none !important;
            }

            .card {
                border: none;
            }

            /* نقل الوقت إلى اليمين */
            body::before {
                content: '';
                display: block;
                position: absolute;
                top: 10px;
                right: 10px; /* تحريك الوقت إلى اليمين */
                text-align: right;
            }

            /* يمكنك إضافة تنسيقات أخرى للطباعة هنا */
        }

    </style>
@endpush
