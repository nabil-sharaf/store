@extends('admin.layouts.app')

@section('page-title')
    تعديل العرض
@endsection

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-md-10 col-sm-12">
                <div class="card">
                    <div class="card-header bg-info text-white ">
                        <h4 class="card-title float-left">تعديل عرض {{$offer->offer_name}}</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.offers.update', $offer->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="form-group">
                                <label for="offer_name">اسم العرض</label>
                                <input type="text" name="offer_name" class="form-control"
                                       value="{{ old('offer_name', $offer->offer_name) }}"
                                       placeholder="أدخل اسم العرض" required>
                                @if ($errors->has('offer_name'))
                                    <span class="text-danger">{{ $errors->first('offer_name') }}</span>
                                @endif
                            </div>
                            <div class="form-group">
                                <label for="product_id">المنتج الذي سيطبق عليه العرض</label>
                                <select name="product_id" class="form-control select2" required>
                                    <option value="">اختر منتجًا</option>
                                    @foreach($products as $product)
                                        <option value="{{ $product->id }}" {{$product->id == $offer->product_id ? 'selected':''}}>{{ $product->name }}</option>
                                    @endforeach
                                </select>
                                @error('product_id')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="offer_quantity">الكمية التي سيطبق عليها العرض</label>
                                <input type="number" name="offer_quantity" class="form-control"
                                       value="{{ old('offer_quantity', $offer->offer_quantity) }}"
                                       placeholder="أدخل الكمية المطلوبة" required>
                                @if ($errors->has('offer_quantity'))
                                    <span class="text-danger">{{ $errors->first('offer_quantity') }}</span>
                                @endif
                            </div>

                            <div class="form-group">
                                <label for="free_quantity">الكمية المجانية</label>
                                <input type="number" name="free_quantity" class="form-control"
                                       value="{{ old('free_quantity', $offer->free_quantity) }}"
                                       placeholder="أدخل الكمية المجانية" required>
                                @if ($errors->has('free_quantity'))
                                    <span class="text-danger">{{ $errors->first('free_quantity') }}</span>
                                @endif
                            </div>

                            <div class="form-group">
                                <label for="customer_type">نوع العميل المستفيد</label>
                                <select name="customer_type" class="form-control">
                                    <option value="regular" {{ $offer->customer_type == 'regular' ? 'selected' : '' }}>عميل القطاعي</option>
                                    <option value="goomla" {{ $offer->customer_type == 'goomla' ? 'selected' : '' }}>عميل الجملة</option>
                                    <option value="all" {{ $offer->customer_type == 'all' ? 'selected' : '' }}>كل العملاء</option>
                                </select>
                                @if ($errors->has('customer_type'))
                                    <span class="text-danger">{{ $errors->first('customer_type') }}</span>
                                @endif
                            </div>

                            <div class="form-group">
                                <label for="start_date">تاريخ البداية</label>
                                <input type="date" name="start_date" class="form-control"
                                       value="{{ old('start_date', $offer->start_date->format('Y-m-d')) }}"
                                       required>
                                @if ($errors->has('start_date'))
                                    <span class="text-danger">{{ $errors->first('start_date') }}</span>
                                @endif
                            </div>

                            <div class="form-group">
                                <label for="end_date">تاريخ النهاية</label>
                                <input type="date" name="end_date" class="form-control"
                                       value="{{ old('end_date', $offer->end_date->format('Y-m-d')) }}"
                                       required>
                                @if ($errors->has('end_date'))
                                    <span class="text-danger">{{ $errors->first('end_date') }}</span>
                                @endif
                            </div>

                            <button type="submit" class="btn btn-info btn-block">تعديل العرض</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
