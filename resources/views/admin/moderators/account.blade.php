@extends('admin.layouts.app')

@section('page-title')
    تفاصيل الحساب
@endsection

@section('content')
    <div class="container mt-4">
        <div class="row mb-3">
            <div class="col">
                <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary mb-3">العودة إلى  الرئيسة</a>
            </div>
        </div>

        <div class="card shadow-sm mb-4">
            <div class="card-header bg-info text-white">
                <h3 id='admin-name-label' class="mb-0">{{ $admin->name }}</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <p><strong>البريد الإلكتروني:</strong> {{ $admin->email }}</p>
                        <p id="admin-status-label"><strong>حالة الحساب:</strong>مفعل</p>
                        <p><strong>تاريخ الانضمام:</strong> {{ $admin->created_at->format('d-m-Y') }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <p><strong>الصلاحيات :</strong>
                            @if($admin->roles->isNotEmpty())
                                <span class="badge bg-gradient-blue">{{ $admin->roles->pluck('name')->join(', ') }}</span>
                            @else
                                لا توجد صلاحيات
                            @endif
                        </p>
                    </div>
                </div>
            </div>
            <div class="card-footer d-flex justify-content-between">
                <a href="{{ route('admin.account.edit', $admin->id) }}" class="btn btn-primary" id="editAdminBtn">تعديل البيانات</a>
            </div>
        </div>
    </div>
@endsection
