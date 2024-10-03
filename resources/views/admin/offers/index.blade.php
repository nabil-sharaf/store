@extends('admin.layouts.app')

@section('page-title')
    العروض
@endsection

@section('content')
    <!-- /.card -->
    <div class="card">
        <div class="card-header">
            <a href="{{ route('admin.offers.create') }}" class="btn btn-primary float-left mr-2">
                <i class="fas fa-plus mr-1"></i> إضافة عرض جديد
            </a>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
            <div class="table-responsive"> <!-- إضافة خاصية الريسبونسيف -->
                <table class="table table-bordered text-center">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>اسم العرض</th>
                        <th>الكمية المطلوبة</th>
                        <th>الكمية المجانية</th>
                        <th> العميل المستفيد</th>
                        <th>تاريخ البداية</th>
                        <th>تاريخ النهاية</th>
                        <th>العمليات</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($offers as $offer)
                        <tr>
                            <td>{{ $loop->iteration }}.</td>
                            <td>{{ $offer->offer_name }}</td>
                            <td>{{ $offer->offer_quantity }}</td>
                            <td>{{ $offer->free_quantity }}</td>
                            <td>
                                @if($offer->customer_type == 'goomla')
                                    عميل الجملة
                                @elseif($offer->customer_type == 'regular')
                                عميل القطاعي
                                @else
                                    كل العملاء
                                @endif
                            </td>
                            <td>{{ $offer->start_date->format('Y-m-d') }}</td> <!-- عرض التاريخ فقط -->
                            <td>{{ $offer->end_date->format('Y-m-d') }}</td> <!-- عرض التاريخ فقط -->
                            <td>
                                <a href="{{ route('admin.offers.show', $offer->id) }}" class="btn btn-sm btn-warning mr-1" title="عرض التفاصيل">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.offers.edit', $offer->id) }}" class="btn btn-sm btn-info mr-1" title="تعديل">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.offers.destroy', $offer->id) }}" method="POST" style="display:inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" title="حذف" onclick="return confirm('هل أنت متأكد من حذف هذا العرض؟')">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8">لا توجد عروض حالياً</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <!-- /.card-body -->
        <div class="card-footer">
            {{ $offers->links('vendor.pagination.custom') }}
        </div>
    </div>
@endsection
