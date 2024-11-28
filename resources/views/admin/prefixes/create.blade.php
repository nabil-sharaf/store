@extends('admin.layouts.app')

@section('content')
    <div class="card card-info">
        <div class="card-header">
            <h3 class="card-title float-left">إضافة بريفكس جديد</h3>
        </div>
        <!-- /.card-header -->
        <!-- form start -->
        <form class="form-horizontal" action="{{ route('admin.prefixes.store') }}" method="POST" dir="rtl">
            @csrf
            <div class="card-body">
                <div class="form-group row">
                    <label for="inputPrefixCode" class="col-sm-2 control-label">كود البريفكس</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control @error('prefix_code') is-invalid @enderror" id="inputPrefixCode" placeholder="أدخل كود البريفكس" name="prefix_code" value="{{ old('prefix_code') }}">
                        @error('prefix_code')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>


                <div class="form-group row">
                    <label for="inputNameAr" class="col-sm-2 control-label">الوصف بالعربية</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control @error('name_ar') is-invalid @enderror" id="inputNameAr" placeholder="أدخل الوصف بالعربية" name="name_ar" value="{{ old('name_ar') }}">
                        @error('name_ar')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="form-group row">
                    <label for="inputNameEn" class="col-sm-2 control-label">الوصف بالإنجليزية</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control @error('name_en') is-invalid @enderror" id="inputNameEn" placeholder="أدخل الوصف بالإنجليزية" name="name_en" value="{{ old('name_en') }}">
                        @error('name_en')<div class="invalid-feedback">{{ $message }}</div>@enderror
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
