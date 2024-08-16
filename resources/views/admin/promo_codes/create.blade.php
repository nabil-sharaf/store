@extends('admin.layouts.app')
@section('page-title')
    إضافة برومو كود
@endsection

@section('content')
    <div class="card card-info">
        <div class="card-header">
            <h3 class="card-title float-left">إضافة برومو كود جديد</h3>
        </div>
        <!-- /.card-header -->
        <!-- form start -->
        <form class="form-horizontal" action="{{ route('admin.promo-codes.store') }}" method="POST" dir="rtl">
            @csrf
            <div class="card-body">

                <!-- اسم البرومو كود -->
                <div class="form-group row">
                    <label for="inputCode" class="col-sm-2 control-label">كود البرومو</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control @error('code') is-invalid @enderror" id="inputCode" placeholder="أدخل كود البرومو" name='code' value="{{ old('code') }}">
                        @error('code')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <!-- نوع الخصم -->
                <div class="form-group row mt-4">
                    <label for="inputDiscountType" class="col-sm-2 control-label">نوع الخصم</label>
                    <div class="col-sm-10">
                        <select class="form-control @error('discount_type') is-invalid @enderror" id="inputDiscountType" name="discount_type">
                            <option value="percentage" {{ old('discount_type') == 'percentage' ? 'selected' : '' }}>نسبة مئوية</option>
                            <option value="fixed" {{ old('discount_type') == 'fixed' ? 'selected' : '' }}>مبلغ ثابت</option>
                        </select>
                        @error('discount_type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <!-- قيمة الخصم -->
                <div class="form-group row mt-4">
                    <label for="inputDiscount" class="col-sm-2 control-label">قيمة الخصم</label>
                    <div class="col-sm-10">
                        <input type="number" class="form-control @error('discount') is-invalid @enderror" id="inputDiscount" placeholder="أدخل قيمة الخصم" name='discount' value="{{ old('discount') }}" step="0.01">
                        @error('discount')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <!-- تاريخ البداية -->
                <div class="form-group row mt-4">
                    <label for="inputStartDate" class="col-sm-2 control-label">تاريخ البداية</label>
                    <div class="col-sm-10">
                        <input type="date" class="form-control @error('start_date') is-invalid @enderror" id="inputStartDate" name='start_date' value="{{ old('start_date') }}">
                        @error('start_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <!-- تاريخ النهاية -->
                <div class="form-group row mt-4">
                    <label for="inputEndDate" class="col-sm-2 control-label">تاريخ النهاية</label>
                    <div class="col-sm-10">
                        <input type="date" class="form-control @error('end_date') is-invalid @enderror" id="inputEndDate" name='end_date' value="{{ old('end_date') }}">
                        @error('end_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <!-- الحد الأدنى للمبلغ (للخصومات الثابتة) -->
                <div class="form-group row mt-4">
                    <label for="inputMinAmount" class="col-sm-2 control-label">الحد الأدنى للمبلغ</label>
                    <div class="col-sm-10">
                        <input type="number" class="form-control @error('min_amount') is-invalid @enderror" id="inputMinAmount" placeholder="أدخل الحد الأدنى للمبلغ المطلوب لتفعيل البرومو" name='min_amount' value="{{ old('min_amount',0) }}" step="0.01" min="0" >
                        @error('min_amount')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

            </div>
            <!-- /.card-body -->
            <div class="card-footer">
                <button type="submit" class="btn btn-info float-right">حفظ البيانات</button>
            </div>
            <!-- /.card-footer -->
        </form>
    </div>
@endsection
