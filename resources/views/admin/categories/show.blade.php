@extends('admin.layouts.app')
@section('page-title')
تفاصيل القسم: {{ $category->name }}
@endsection
@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">تفاصيل القسم</h3>
    </div>
    <div class="card-body">
        <!-- Breadcrumbs -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.categories.index') }}">الأقسام</a></li>
                @foreach($breadcrumbs as $breadcrumb)
                    @if($loop->last)
                        <li class="breadcrumb-item active" aria-current="page">{{ $breadcrumb->name }}</li>
                    @else
                        <li class="breadcrumb-item"><a href="{{ route('admin.categories.show', $breadcrumb->id) }}">{{ $breadcrumb->name }}</a></li>
                    @endif
                @endforeach
            </ol>
        </nav>

        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">معلومات القسم</h5>
                    </div>
                    <div class="card-body">
                        <p><strong>الاسم:</strong> {{ $category->name }}</p>
                        <p><strong>الوصف:</strong> {{ strip_tags($category->description) }}</p>
                        <p><strong>النوع:</strong>
                            @if($category->parent_id)
                                <span class="badge bg-gradient-gray-dark">فرعي</span>
                            @else
                                <span class="badge bg-gradient-blue">رئيسي</span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0">القسم الأب</h5>
                    </div>
                    <div class="card-body">
                        @if($category->parent)
                            <p><strong>الاسم:</strong> <a href="{{ route('admin.categories.show', $category->parent->id) }}">{{ $category->parent->name }}</a></p>
                            <p><strong>الوصف:</strong> {{ Str::limit(strip_tags($category->parent->description), 100) }}</p>
                        @else
                            <p>هذا قسم رئيسي وليس له قسم أب.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">الأقسام الفرعية</h5>
                    </div>
                    <div class="card-body">
                        @if($category->children->count() > 0)
                            <div class="list-group">
                                @foreach($category->children as $child)
                                    <a href="{{ route('admin.categories.show', $child->id) }}" class="list-group-item list-group-item-action">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h5 class="mb-1">{{ $child->name }}</h5>
                                            <small>{{ $child->created_at->diffForHumans() }}</small>
                                        </div>
                                        <p class="mb-1">{{ Str::limit($child->description, 100) }}</p>
                                    </a>
                                @endforeach
                            </div>
                        @else
                            <p>لا يوجد أقسام فرعية لهذا القسم.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card-footer">
        <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">عودة للقائمة</a>
        <a href="{{ route('admin.categories.edit', $category->id) }}" class="btn btn-primary">تعديل</a>
    </div>
</div>
@endsection
