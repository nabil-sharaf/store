@extends('admin.layouts.app')
@section('page-title')
الاقسام
@endsection
@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="mb-0">الأقسام</h3>
            <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
                <i class="fas fa-plus mr-1"></i> إضافة قسم جديد
            </a>
        </div>

        <div class="row">
            @forelse($categories as $category)
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <!-- اسم القسم في سطر منفصل -->
                            <h5 class="card-title">
                                {{ $category->name }}
                                <span class="badge {{ $category->parent_id ? 'bg-secondary' : 'bg-primary' }}">
                                    {{ $category->parent_id ? 'فرعي' : 'رئيسي' }}
                                </span>
                            </h5>
                            <!-- الوصف في سطر جديد تحت الاسم -->
                            <p class="card-text">
                                وصف القسم : {!! Str::limit(strip_tags($category->description), 50, '...') !!}
                            </p>
                            <!-- إضافة الصورة تحت الوصف -->
                            @if($category->image)
                                <div class="text-center mt-3">
                                    <img src="{{ asset('storage/' . $category->image) }}"
                                         alt="{{ $category->name }}"
                                         class="img-fluid rounded"
                                         style="max-height: 100px; object-fit: cover;">
                                </div>
                            @endif
                        </div>
                        <div class="card-footer d-flex justify-content-between">
                            <div class="btn-group" role="group">
                                <a href="{{ route('admin.categories.show', $category->id) }}" class="btn btn-sm btn-warning mr-1" title="عرض التفاصيل">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.categories.edit', $category->id) }}" class="btn btn-sm btn-info mr-1" title="تعديل">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @if(auth('admin')->user()->hasAnyRole(['superAdmin']))
                                    <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" style="display:inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" title="حذف" onclick="return confirm('هل أنت متأكد من حذف هذا القسم؟')">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-warning text-center">لا يوجد أقسام حاليا</div>
                </div>
            @endforelse
        </div>

        <div class="d-flex justify-content-center">
            {{ $categories->links('vendor.pagination.custom') }}
        </div>
    </div>
@endsection

@push('styles')
<style>
    .card-title{
        float: unset;
    }
</style>
@endpush
