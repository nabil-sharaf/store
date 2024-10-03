@extends('admin.layouts.app')

@section('page-title')
    إضافة عرض جديد
@endsection

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-md-10 col-sm-12">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h4 class="card-title float-left">إضافة عرض جديد</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.offers.store') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="offer_name">اسم العرض</label>
                                <input type="text" name="offer_name" class="form-control" placeholder="أدخل اسم العرض" value="{{ old('offer_name') }}" required>
                                @error('offer_name')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="product_id">المنتج الذي سيطبق عليه العرض</label>
                                <select name="product_id" class="form-control select2" required>
                                    <option value="">اختر منتجًا</option>
                                    @foreach($products as $product)
                                        <option value="{{ $product->id }}">{{ $product->name }}</option>
                                    @endforeach
                                </select>
                                @error('product_id')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="offer_quantity">الكمية التي سيطبق عليها العرض</label>
                                <input type="number" name="offer_quantity" class="form-control" placeholder="أدخل الكمية المطلوبة" value="{{ old('offer_quantity') }}" required>
                                @error('offer_quantity')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="free_quantity">الكمية المجانية</label>
                                <input type="number" name="free_quantity" class="form-control" placeholder="أدخل الكمية المجانية" value="{{ old('free_quantity') }}" required>
                                @error('free_quantity')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="customer_type">نوع العميل المستفيد</label>
                                <select name="customer_type" class="form-control" required>
                                    <option value="regular" {{ old('customer_type') == 'regular' ? 'selected' : '' }}>عميل القطاعي</option>
                                    <option value="goomla" {{ old('customer_type') == 'wholesale' ? 'selected' : '' }}>عميل الجملة</option>
                                    <option value="all" {{ old('customer_type') == 'all' ? 'selected' : '' }}>كل العملاء</option>
                                </select>
                                @error('customer_type')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="start_date">تاريخ بداية العرض</label>
                                <input type="date" name="start_date" class="form-control" value="{{ old('start_date') }}" required>
                                @error('start_date')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="end_date">تاريخ نهاية العرض</label>
                                <input type="date" name="end_date" class="form-control" value="{{ old('end_date') }}" required>
                                @error('end_date')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-success btn-block">إضافة العرض</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
