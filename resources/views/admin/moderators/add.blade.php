@extends('admin.layouts.app')

@section('page-title')
    إضافة مشرف جديد
@endsection

@section('content')
    <div class="card card-info">
        <div class="card-header">
            <h3 class="card-title float-left">إضافة مشرف جديد</h3>
        </div>
        <!-- /.card-header -->
        <!-- form start -->
        <form class="form-horizontal" action="{{ route('admin.moderators.store') }}" method="POST" dir="rtl">
            @csrf
            <div class="card-body">

                <!-- حقل الاسم -->
                <div class="form-group row">
                        <label for="name" class="col-sm-2 control-label">الاسم</label>
                    <div class="col-sm-10">
                        <input type="text" name="name" class="form-control" id="name" value="{{ old('name') }}" required>
                        @error('name')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                        </div>
                </div>

                <div class="form-group row">
                    <label for="inputEmail" class="col-sm-2 control-label">البريد الإلكتروني</label>
                    <div class="col-sm-10">
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="inputEmail" placeholder="أدخل البريد الإلكتروني" name="email" value="{{ old('email') }}">
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="form-group row mt-4">
                    <label for="inputPassword" class="col-sm-2 control-label">كلمة المرور</label>
                    <div class="col-sm-10">
                        <input type="password" class="form-control @error('password') is-invalid @enderror" id="inputPassword" placeholder="أدخل كلمة المرور" name="password">
                        @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="form-group row mt-4">
                    <label for="inputConfirmPassword" class="col-sm-2 control-label">تأكيد كلمة المرور</label>
                    <div class="col-sm-10">
                        <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror" id="inputConfirmPassword" placeholder="أعد إدخال كلمة المرور" name="password_confirmation">
                        @error('password_confirmation')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="form-group row mt-4">
                    <label for="role_id" class="col-sm-2 control-label">الصلاحيات</label>
                    <div class="col-sm-10">
                        <select class="form-control @error('role_id') is-invalid @enderror" id="role_id" name="role_id">
                            <option value="">اختر الرول</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->name }}">{{ $role->name }}</option>
                            @endforeach
                        </select>
                        @error('role_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
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
