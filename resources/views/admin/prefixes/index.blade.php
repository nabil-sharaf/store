@extends('admin.layouts.app')
@section('page-title')
     اختصارات الأكواد
@endsection
@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <a href="{{ route('admin.prefixes.create') }}" class="btn btn-primary">
                <i class="fas fa-plus mr-1"></i> إضافة بريفكس جديد
            </a>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-striped text-center table-hover">
                <thead>
                <tr>
                    <th>كود البريفكس</th>
                    <th>الوصف بالانجليزية </th>
                    <th>الوصف بالعربية</th>
                    <th>العمليات</th>
                </tr>
                </thead>
                <tbody>
                @forelse($prefixes as $prefix)
                    <tr>
                        <td>{{ $prefix->prefix_code  }}</td>
                        <td>{{ $prefix->name ??'--' }}</td>
                        <td>{{ Str::limit($prefix->description, 50, '...') ??"--"}}</td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('admin.prefixes.edit', $prefix->id) }}" class="btn btn-sm btn-info mr-1" title="تعديل">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.prefixes.destroy', $prefix->id) }}" method="POST" style="display:inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" title="حذف" onclick="return confirm('هل أنت متأكد من الحذف؟')">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center">لا يوجد بريفكسات حاليا</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-center">
            {{ $prefixes->links('vendor.pagination.custom') }}
        </div>
    </div>
@endsection
