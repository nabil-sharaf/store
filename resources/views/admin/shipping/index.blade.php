@extends('admin.layouts.app')

@section('content')
    <div class="container mt-5">
        <h2 class="text-center">إدارة تكاليف الشحن</h2>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <!-- عرض أخطاء الفاليديشين -->
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- فورم إضافة محافظة جديدة -->
        <div class="card mb-4">
            <div class="card-header">إضافة محافظة جديدة</div>
            <div class="card-body">
                <form action="{{ route('admin.shipping-rates.store') }}" method="POST">
                    @csrf
                    <div class="row mb-3">
                        <div class="col">
                            <label for="state">المحافظة</label>
                            <input type="text" class="form-control @error('state') is-invalid @enderror" name="state" value="{{ old('state') }}" required>
                            @error('state')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col">
                            <label for="shipping_cost">تكلفة الشحن</label>
                            <input type="number" step="0.01" class="form-control @error('shipping_cost') is-invalid @enderror" name="shipping_cost" value="{{ old('shipping_cost') }}" required>
                            @error('shipping_cost')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <button type="submit" class="btn btn-success">إضافة</button>
                </form>
            </div>
        </div>

        <!-- جدول المحافظات وتكاليف الشحن -->
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-dark">
                <tr>
                    <th>المحافظة</th>
                    <th>تكلفة الشحن</th>
                    <th>إجراءات</th>
                </tr>
                </thead>
                <tbody>
                @foreach($shippingRates as $rate)
                    <tr>
                        <td>{{ $rate->state }}</td>
                        <td>
                            <form action="{{ route('admin.shipping-rates.update', $rate->id) }}" method="POST" class="d-flex justify-content-between">
                                @csrf
                                <input type="number" step="0.01" class="state-input form-control me-2" name="shipping_cost" value="{{ $rate->shipping_cost }}" required>
                                <button type="submit" class="btn btn-sm btn-primary">تحديث</button>
                            </form>
                        </td>
                        <td>
                            <form action="{{ route('admin.shipping-rates.destroy', $rate->id) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من حذف هذه المحافظة؟')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">حذف</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
@push('styles')
    <style>
        .state-input{
            font-size: 16px!important;
            padding:10px !important;
        }
    </style>
@endpush
