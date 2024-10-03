@extends('admin.layouts.app')

@section('page-title')
    المشرفين
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <a href="{{ route('admin.moderators.create') }}" class="btn btn-primary float-left mr-2">
                <i class="fas fa-plus mr-1"></i> إضافة مشرف جديد
            </a>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
            <div class="row">
                @forelse($moderators as $moderator)
                    <div class="col-md-4">
                        <div class="card mb-4 shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title"> #{{ $loop->iteration }}</h5>
                                <p class="card-text">
                                    <strong>البريد الإلكتروني:</strong> {{ $moderator->email }}<br>
                                    <strong>الصلاحيات:</strong>
                                    @foreach($moderator->roles as $role)
                                        <span class="badge bg-gradient-blue">{{ $role->name }}</span>
                                    @endforeach
                                </p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="btn-group">
                                        <a href="{{ route('admin.moderators.show', $moderator->id) }}" class="btn btn-sm btn-warning mx-1" title="عرض التفاصيل">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.moderators.edit', $moderator->id) }}" class="btn btn-sm btn-info mx-1" title="تعديل">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.moderators.destroy', $moderator->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('هل أنت متأكد من حذف هذا المشرف؟')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger mx-1" title="حذف">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="alert alert-warning text-center">لا يوجد مشرفين حالياً</div>
                    </div>
                @endforelse
            </div>
        </div>
        <!-- /.card-body -->
        <div class="card-footer">
            {{ $moderators->links('vendor.pagination.custom') }}
        </div>
    </div>
@endsection
