@extends('admin.layouts.app')

@if ($errors->any())
    <div class="alert alert-danger">
        @foreach ($errors->all() as $error)
            <p>{{ $error }}</p>
        @endforeach
    </div>
@endif

@section('page-title')
    برومو كودز
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <a href="{{ route('admin.promo-codes.create') }}" class="btn btn-primary float-left mr-2">
                <i class="fas fa-plus mr-1"></i> إضافة برومو كود جديد
            </a>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
            <table class="table table-bordered text-center" id="promoCodesTable">
                <thead>
                <tr>
                    <th>#</th>
                    <th>كود البرومو</th>
                    <th>نوع الخصم</th>
                    <th>قيمة الخصم</th>
                    <th>تاريخ البداية</th>
                    <th>تاريخ النهاية</th>
                    <th>الحالة</th>
                    <th>العمليات</th>
                </tr>
                </thead>
                <tbody>
                @foreach($promoCodes as $promoCode)
                    <tr>
                        <td>{{ $loop->iteration }}.</td>
                        <td>{{ $promoCode->code }}</td>
                        <td>{{ $promoCode->discount_type == 'percentage' ? 'نسبة مئوية' : 'مبلغ ثابت' }}</td>
                        <td>{{ $promoCode->discount }}{{ $promoCode->discount_type == 'percentage' ? '%' : ' جنيه' }}</td>
                        <td>{{ $promoCode->start_date->format('Y-m-d') }}</td>
                        <td>{{ $promoCode->end_date->format('Y-m-d') }}</td>
                        <td>
                            <!-- حالة التفعيل -->
                            @if($promoCode->active)
                                <span class="badge badge-success">مفعل</span>
                            @else
                                <span class="badge badge-danger">غير مفعل</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.promo-codes.show', $promoCode->id) }}" class="btn btn-sm btn-warning mr-1" title="عرض التفاصيل">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('admin.promo-codes.edit', $promoCode->id) }}" class="btn btn-sm btn-info mr-1" title="تعديل">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.promo-codes.destroy', $promoCode->id) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" title="حذف" onclick="return confirm('هل أنت متأكد من حذف هذا البرومو كود؟')">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <!-- /.card-body -->
        <div class="card-footer">
            {{ $promoCodes->links('vendor.pagination.custom') }}
        </div>
    </div>
@endsection

@push('styles')
    <style>
        table th a {
            text-decoration: none !important;
        }
        table th a i {
            text-decoration: none !important;
        }
    </style>
@endpush

@push('scripts')
    <script>
        $(document).ready(function() {
            let sortDirection = 'asc';

            $('#promoCodesTable th[data-sort]').click(function() {
                const column = $(this).data('sort');
                const table = $('#promoCodesTable tbody');
                const rows = table.find('tr').toArray();

                rows.sort(function(a, b) {
                    const cellA = $(a).find('td').eq($(this).index()).text();
                    const cellB = $(b).find('td').eq($(this).index()).text();

                    if (sortDirection === 'asc') {
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
        });
    </script>
@endpush
