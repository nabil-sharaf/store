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
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4>إدارة برومو كود</h4>
            <a href="{{ route('admin.promo-codes.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> إضافة برومو كود جديد
            </a>
        </div>

        @forelse($promoCodes as $promoCode)
            <div class="card mb-3 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <!-- البرومو كود في الصف العلوي مع البيانات -->
                        <h5 class="card-title">
                            <span class="badge badge-secondary p-1" style="font-size: 1.2rem;">{{ $promoCode->code }}</span>
                        </h5>

                        <!-- عرض رقم التكرار في الزاوية اليسرى مع الأيقونات -->
                        <p class="text-muted mb-0" style="font-size: 0.9rem; position: absolute; left: 10px;">#{{ $loop->iteration }}</p>
                    </div>

                    <p class="card-text">
                        <strong>نوع الخصم:</strong> {{ $promoCode->discount_type == 'percentage' ? 'نسبة مئوية' : 'مبلغ ثابت' }}<br>
                        <strong>قيمة الخصم:</strong> {{ $promoCode->discount }}{{ $promoCode->discount_type == 'percentage' ? '%' : ' جنيه' }}<br>
                        <strong>تاريخ البداية:</strong> {{ $promoCode->start_date->format('Y-m-d') }}<br>
                        <strong>تاريخ النهاية:</strong> {{ $promoCode->end_date->format('Y-m-d') }}<br>
                        <strong>الحالة:</strong>
                        @if($promoCode->active)
                            <span class="badge badge-success">مفعل</span>
                        @else
                            <span class="badge badge-danger">غير مفعل</span>
                        @endif
                    </p>

                    <!-- الأيقونات والعمليات -->
                    <div class="d-flex justify-content-start">
                        <a href="{{ route('admin.promo-codes.show', $promoCode->id) }}" class="btn btn-warning btn-sm mr-1" title="عرض التفاصيل">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="{{ route('admin.promo-codes.edit', $promoCode->id) }}" class="btn btn-info btn-sm mr-1" title="تعديل">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('admin.promo-codes.destroy', $promoCode->id) }}" method="POST" class="d-inline-block" onsubmit="return confirm('هل أنت متأكد من حذف هذا البرومو كود؟')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" title="حذف">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="alert alert-info">لا توجد برومو كودات حالياً</div>
        @endforelse

        <!-- روابط التصفح -->
        <div class="d-flex justify-content-center mt-3">
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
