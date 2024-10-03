@extends('admin.layouts.app')

@section('page-title')
    العروض
@endsection

@section('content')
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4>إدارة العروض</h4>
            <a href="{{ route('admin.offers.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> إضافة عرض جديد
            </a>
        </div>

        @forelse($offers as $offer)
            <div class="card mb-3 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">{{ $offer->offer_name }}</h5>
                    <p class="card-text">
                        <strong>المنتج:</strong> {{ $offer->product->name }}<br>
                        <strong>الكمية المطلوبة:</strong> {{ $offer->offer_quantity }}<br>
                        <strong>الكمية المجانية:</strong> {{ $offer->free_quantity }}<br>
                        <strong>العميل المستفيد:</strong>
                        @if($offer->customer_type == 'goomla')
                            عميل الجملة
                        @elseif($offer->customer_type == 'regular')
                            عميل القطاعي
                        @else
                            كل العملاء
                        @endif
                        <br>
                        <strong>تاريخ النهاية:</strong> {{ $offer->end_date->format('Y-m-d') }}
                    </p>
                    <div class="d-flex justify-content-between">
                        <div>
                            <a href="{{ route('admin.offers.show', $offer->id) }}" class="btn btn-warning btn-sm" title="عرض التفاصيل">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('admin.offers.edit', $offer->id) }}" class="btn btn-info btn-sm" title="تعديل">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.offers.destroy', $offer->id) }}" method="POST" class="d-inline-block" onsubmit="return confirm('هل أنت متأكد من حذف هذا العرض؟')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" title="حذف">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                        </div>
                        <span class="text-muted">{{ $loop->iteration }}.</span>
                    </div>
                </div>
            </div>
        @empty
            <div class="alert alert-info">لا توجد عروض حالياً</div>
        @endforelse

        <!-- روابط التصفح -->
        <div class="d-flex justify-content-center mt-3">
            {{ $offers->links('vendor.pagination.custom') }}
        </div>
    </div>
@endsection
