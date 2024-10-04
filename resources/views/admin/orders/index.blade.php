@extends('admin.layouts.app')
@if ($errors->any())
    <div class="alert alert-danger">
        @foreach ($errors->all() as $error)
            <p>{{ $error }}</p>
        @endforeach
    </div>
@endif
@section('page-title')
    الطلبات
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-12 col-md-4 mb-2">
                    <a href="{{ route('admin.orders.create') }}" class="btn btn-primary w-100">
                        <i class="fas fa-plus mr-1"></i> إضافة طلب جديد
                    </a>
                </div>

                <div class="col-12 col-md-8">
                    <div class="row">
                        <div class="col-12 col-md-6 mb-2">
                            <form id="dateFilterForm" method="GET">
                                <label for="dateFilter" class="d-block mb-1">بحث بتاريخ الأوردر</label>
                                <div class="input-group">
                                    <input type="date" name="date" id="dateFilter" class="form-control" value="{{ request('date') }}">
                                    <div class="input-group-append">
                                        <button type="submit" class="btn btn-primary">بحث</button>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <div class="col-12 col-md-6 mb-2">
                            <form id="statusFilterForm" method="GET">
                                <label for="statusFilter" class="d-block mb-1">فلتر بحالة الأوردر</label>
                                <select name="status" id="statusFilter" class="select2 form-control w-100">
                                    <option value="">اختيار الكل</option>
                                    <option value="1">جاري المعالجة</option>
                                    <option value="2">جاري الشحن</option>
                                    <option value="3">تم التسليم</option>
                                    <option value="4">ملغي</option>
                                </select>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered text-center" id="ordersTable">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th data-sort="user">اسم المستخدم <i class="fas fa-sort"></i></th>
                        <th data-sort="created_at">تاريخ الاوردر <i class="fas fa-sort"></i></th>
                        <th>قيمة الخصم</th>
                        <th data-sort="total_price">الإجمالي بعد الخصم <i class="fas fa-sort"></i></th>
                        <th>الحالة</th>
                        <th>العمليات</th>
                    </tr>
                    </thead>
                    <tbody id="ordersTableBody">
                    @foreach($orders as $order)
                        <tr>
                            <td>{{ $loop->iteration }}.</td>
                            <td>{{ $order->user?->name ?? ' Guest زائر' }}</td>
                            <td>{{ $order->created_at->format('Y-m-d') }}</td>
                            <td>{{ $order->total_price - $order->total_after_discount == 0 ? "لا يوجد " : ($order->total_price - $order->total_after_discount)." جنيه "}}</td>
                            <td>{{ $order->total_after_discount }} جنيه</td>
                            <td>
                                @if($order->status->id != 3 && $order->status->id != 4)
                                    <form class="status-form" id="form{{$order->id}}" data-order-id="{{ $order->id }}">
                                        @csrf
                                        @method('PUT')
                                        <select name="status" class="form-control order-status" data-order-id="{{ $order->id }}">
                                            @if($order->status->id != 2)
                                                <option id='status-1' value="1" {{ $order->status->id == 1 ? 'selected' : '' }}>قيد المعالجة</option>
                                            @endif
                                            <option value="2" {{ $order->status->id == 2 ? 'selected' : '' }}>جاري الشحن</option>
                                            <option value="3" {{ $order->status->id == 3 ? 'selected' : '' }}>تم التسليم</option>
                                            <option value="4" {{ $order->status->id == 4 ? 'selected' : '' }}>ملغي</option>
                                        </select>
                                    </form>
                                @elseif($order->status->id == 3)
                                    <span class="btn-sm btn-success">{{ $order->status->name }}</span>
                                @elseif($order->status->id == 4)
                                    <span class="btn-sm btn-danger">{{ $order->status->name }}</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-warning mr-1 mb-1" title="عرض التفاصيل">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @if($order->status->id == 1)
                                    <a href="{{ route('admin.orders.edit', $order->id) }}" class="btn btn-sm btn-info mr-1 mb-1 edit-st{{$order->id}}" title="تعديل">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                @else
                                    <a href="#" class="btn btn-sm btn-secondary mr-1 mb-1 disabled edit-st{{$order->id}}" title="غير متاح">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card-footer">
            {{ $orders->links('vendor.pagination.custom') }}
        </div>
    </div>
@endsection
@push('styles')
    <style>
        @media (max-width: 767px) {
            .form-inline {
                display: block;
            }
            .form-inline label {
                display: block;
                margin-bottom: 5px;
            }
            .form-inline .form-control {
                width: 100%;
            }

            #dateFilter{
                width:80% !important;;
            }
        }

        .table-responsive {
            margin-top: 20px;
        }

        .input-group {
            width: 100%;
        }

        .input-group .form-control {
            border-radius: 0.25rem;
        }

        .input-group .btn {
            border-radius: 0 0.25rem 0.25rem 0;
        }

        .status-form {
            margin: 0;
        }

        .select2 {
            width: 100% !important;
        }

        @media (max-width: 576px) {
            .btn {
                width: 100%;
            }
        }

        @media (min-width: 767px) {
            .date-search{
                padding-left: 10px;
            }

        }

        @media (max-width: 767px) {
            .card-body {
                padding: 0.5rem;
            }

            .table-responsive {
                overflow-x: auto;
            }

            #ordersTable th,
            #ordersTable td {
                white-space: nowrap;
            }

            .btn-sm {
                padding: 0.25rem 0.5rem;
                font-size: 0.75rem;
            }
        }
body{
    overflow: auto;
}
    </style>
@endpush
@push('scripts')
    <script>
        $(document).ready(function() {
            let sortDirection = 'asc';

            $('#ordersTable th[data-sort]').click(function() {
                const column = $(this).data('sort');
                const table = $('#ordersTable tbody');
                const rows = table.find('tr').toArray();

                rows.sort(function(a, b) {
                    const cellA = $(a).find('td').eq($(this).index()).text();
                    const cellB = $(b).find('td').eq($(this).index()).text();

                    if(sortDirection === 'asc') {
                        return cellA.localeCompare(cellB, 'ar', { numeric: true });
                    } else {
                        return cellB.localeCompare(cellA, 'ar', { numeric: true });
                    }
                }.bind(this));

                sortDirection = (sortDirection === 'asc') ? 'desc' : 'asc';

                // تحديث السهم بجانب العنوان
                $(this).find('i').removeClass().addClass(sortDirection === 'asc' ? 'fas fa-sort-up' : 'fas fa-sort-down');

                $.each(rows, function(index, row) {
                    table.append(row);
                });
            });

            // البحث عبر AJAX عند تغيير الحالة
            $('#statusFilter').on('change', function() {
                var status = $(this).val();
                var url = '{{ route("admin.orders.index") }}';

                $.ajax({
                    url: url,
                    type: 'GET',
                    data: { status: status },
                    success: function(response) {
                        // تحديث الجدول بالنتائج الجديدة
                        $('#ordersTableBody').html($(response).find('#ordersTableBody').html());

                        // تفعيل أوامر التعديل والحالة الجديدة بعد تحميل النتائج
                        activateOrderStatusChange();
                    },
                    error: function(xhr, status, error) {
                        toastr.error('حدث خطأ أثناء البحث');
                    }
                });
            });

            // تفعيل تحديث حالة الطلبات عند البحث
            function activateOrderStatusChange() {
                $('.order-status').on('change', function() {
                    var orderId = $(this).data('order-id');
                    var status = $(this).val();
                    var token = $('input[name="_token"]').val();
                    var mySelect = $(this).closest('form');
                    var option1 = $(this).find('#status-1');
                    var editLink = $('.edit-st'+orderId);

                    $.ajax({
                        url: '{{ route("admin.orders.updatestatus", ":id") }}'.replace(':id', orderId),
                        type: 'POST',
                        data: {
                            _token: token,
                            _method: 'Put',
                            status: status
                        },
                        success: function(response) {
                            toastr.success(response.success);
                            if(status != 1){
                                $(editLink).removeClass().addClass('btn btn-sm btn-secondary mr-1 disabled');
                            }
                            if (status ==2 ) {
                                option1.remove();

                            }
                            if (status == 3) {
                                mySelect.replaceWith('<span class="btn-sm btn-success">تم التسليم</span>');
                            }
                            if (status == 4) {
                                mySelect.replaceWith('<span class="btn-sm btn-danger">ملغي</span>');
                            }
                        },
                        error: function(xhr, status, error) {
                            toastr.error('حدث خطأ أثناء تعديل الحالة');
                        }
                    });
                });
            }

            // تفعيل وظائف بعد التحميل الأولي
            activateOrderStatusChange();
        });
    </script>
@endpush
