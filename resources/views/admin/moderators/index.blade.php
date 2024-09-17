@extends('admin.layouts.app')

@section('page-title')
    المشرفين
@endsection

@section('content')
    <!-- /.card -->
    <div class="card">
        <div class="card-header">
            <a href="{{ route('admin.moderators.create') }}" class="btn btn-primary float-left mr-2">
                <i class="fas fa-plus mr-1"></i> إضافة مشرف جديد
            </a>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
            <table class="table table-bordered text-center">
                <thead>
                <tr>
                    <th>#</th>
                    <th>البريد الإلكتروني</th>
                    <th>الرول</th>
                    <th>العمليات</th>
                </tr>
                </thead>
                <tbody>
                @forelse($moderators as $moderator)
                    <tr>
                        <td>{{ $loop->iteration }}.</td>
                        <td>{{ $moderator->email }}</td>
                        <td>
                            @foreach($moderator->roles as $role)
                                <span class="badge bg-gradient-blue">{{ $role->name }}</span>
                            @endforeach
                        </td>
                        <td>
                            <a href="{{ route('admin.moderators.show', $moderator->id) }}" class="btn btn-sm btn-warning mr-1" title="عرض التفاصيل">
                                <i class="fas fa-eye "></i>
                            </a>
                            <a href="{{ route('admin.moderators.edit', $moderator->id) }}" class="btn btn-sm btn-info mr-1" title="تعديل">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.moderators.destroy', $moderator->id) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" title="حذف" onclick="return confirm('هل أنت متأكد من حذف هذا المشرف؟')">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4">لا يوجد مشرفين حالياً</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
        <!-- /.card-body -->
        <div class="card-footer">
            {{ $moderators->links('vendor.pagination.custom') }}
        </div>
    </div>
@endsection
