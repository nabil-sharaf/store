@extends('admin.layouts.app')
@section('page-title')
    تفاصيل الطلب
@endsection

@section('content')
    <div class="card ">
        <div class="card-header @if($order->status->id == 3) bg-success pb-0 @elseif($order->status->id == 4) pb-0 bg-danger @endif">
            <h3 class="card-title pb-0">تفاصيل الطلب رقم #{{ $order->id }}</h3>
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
                    <p><strong>المستخدم:</strong> {{ $order->user->name }}</p>
                    <p><strong> الاجمالي بعد الخصم:</strong> {{ $order->total_after_discount }} جنيه</p>
                    <p><strong>الحالة الحالية:</strong> {{ ucfirst($order->status->name) }}</p>
                </div>
                <div class="col-md-6">
                    @if($order->status->id != 3 && $order->status->id != 4)
                        <form id="statusForm" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="form-group">
                                <label for="status">تغيير حالة الطلب:</label>
                                <select name="status" id="status" class="select2 form-control" style="width: 60%">
                                    <option id="processing" value="1" {{ $order->status->id == 1 ? 'selected' : '' }}>جاري المعالجة</option>
                                    <option value="2" {{ $order->status->id == 2 ? 'selected' : '' }}>جاري الشحن</option>
                                    <option value="3" {{ $order->status->id == 3 ? 'selected' : '' }}>تم التسليم</option>
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
                    <th>الكمية</th>
                    <th>السعر</th>
                    <th>الإجمالي</th>
                </tr>
                </thead>
                <tbody>
                @foreach($order->orderDetails as $detail)
                    <tr>
                        <td>{{ $detail->product->name }}</td>
                        <td>{{ $detail->product_quantity }}</td>
                        <td>{{ $detail->product->price }} ج</td>
                        <td>{{ $detail->product_quantity * $detail->product->price }} ج</td>
                    </tr>
                @endforeach
                <tr>
                    <td colspan="3" class="text-left font-weight-bold"> اجمالي الاوردر </td>
                    <td class="">{{ $order->total_price }} ج</td>
                </tr>
                <tr>
                    <td colspan="3" class="text-left font-weight-bold">قيمة الخصم </td>
                    <td class="">{{ $order->discount }} ج</td>
                </tr>
                <tr class="table-info">
                    <td colspan="3" class="text-left font-weight-bold">السعر الاجمالي بعد الخصم </td>
                    <td class="font-weight-bold">{{ $order->total_after_discount }} ج</td>
                </tr>
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">رجوع إلى قائمة الطلبات</a>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#status').on('change', function() {
                var formData = $('#statusForm').serialize();
                var url = '{{ route("admin.orders.updatestatus", $order->id) }}';
                let status = $('#status').val();

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        toastr.success('تم تحديث الحالة بنجاح!');
                    if(status==2){
                        $('#processing').remove();
                    }
                    if(status==3){
                        $('#statusForm').replaceWith('<div class="alert alert-success p-1">تم تسليم الاوردر ✅</div>')
                    }
                    if(status==4){
                        $('#statusForm').replaceWith('<div class="alert alert-danger p-1"> تم الغاء الطلب ❌</div>')
                    }

                    },
                    error: function(xhr, status, error) {
                        toastr.error('حدث خطأ أثناء تحديث الحالة.');
                    }
                });
            });
        });
    </script>
@endpush
