@extends('admin.layouts.app')

@section('page-title')
    تفاصيل البرومو كود
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">تفاصيل البرومو كود</h3>
        </div>
        <div class="card-body">
            <table class="table table-hover table-bordered promo-details-table">
                <tbody>
                <tr>
                    <th >كود البرومو</th>
                    <td>{{ $promoCode->code }}</td>
                </tr>
                <tr>
                    <th>نوع الخصم</th>
                    <td>{{ $promoCode->discount_type == 'percentage' ? 'نسبة مئوية' : 'مبلغ ثابت' }}</td>
                </tr>
                <tr>
                    <th>قيمة الخصم</th>
                    <td>{{ $promoCode->discount }}{{ $promoCode->discount_type == 'percentage' ? '%' : ' جنيه' }}</td>
                </tr>
                <tr>
                    <th>تاريخ البداية</th>
                    <td>{{ $promoCode->start_date->format('Y-m-d') }}</td>
                </tr>
                <tr>
                    <th>تاريخ النهاية</th>
                    <td>{{ $promoCode->end_date->format('Y-m-d') }}</td>
                </tr>
                <tr>
                    <th>الحد الأدنى لقيمة الأوردر </th>
                    <td>{{ $promoCode->min_amount }} جنيه</td>
                </tr>
                <tr>
                    <th>الحالة</th>
                    <td>
                            <span class="badge {{ $promoCode->active ? 'badge-success' : 'badge-danger' }}">
                                {{ $promoCode->active ? 'مفعل' : 'غير مفعل' }}
                            </span>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
        <!-- /.card-body -->
        <div class="card-footer text-center">
            <a href="{{ route('admin.promo-codes.edit', $promoCode->id) }}" class="btn btn-info btn-lg">
                <i class="fas fa-edit"></i> تعديل البرومو كود
            </a>
            <a href="{{ route('admin.promo-codes.index') }}" class="btn btn-secondary btn-lg">
                <i class="fas fa-arrow-left"></i> العودة إلى قائمة البرومو كودز
            </a>
        </div>
    </div>
@endsection

@push('styles')
    <style>

th{
    width:50%;
    }
    </style>
@endpush
