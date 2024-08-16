@extends('admin.layouts.app')
@section('page-title')
تعديل القسم
@endsection
@section('content')
<div class="card card-info">
  <div class="card-header">
    <h3 class="card-title float-left">تعديل القسم</h3>
  </div>
  <!-- /.card-header -->
  <!-- form start -->
  <form class="form-horizontal" action="{{ route('admin.categories.update', $category->id) }}" method="POST" dir="rtl">
      @csrf
      @method('PUT')
      <div class="card-body">
      <div class="form-group row">
        <label for="inputName" class="col-sm-2 control-label">اسم القسم</label>
        <div class="col-sm-10">
          <input type="text"  class="form-control @error('name') is-invalid @enderror" id="inputName" placeholder="أدخل اسم القسم" name='name' value="{{ old('name', $category->name) }}">
            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
      </div>
      <div class="form-group row mt-4">
        <label for="inputDescription" class="col-sm-2 control-label">الوصف</label>
        <div class="col-sm-10">
            <textarea class="form-control @error('description') is-invalid @enderror" id="inputDescription" rows="3" placeholder="أدخل وصف القسم" name='description'>{{ old('description', $category->description) }}</textarea>
            @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
      </div>
          
      <div class="form-group row">
        <label for="inputName" class="col-sm-2 control-label">الكاتيجوري الأب</label>
        <div class="col-sm-10">
        <select class="form-control @error('parent_id') is-invalid @enderror" id="parent_id" name="parent_id">
            <option value="">بدون أب (رئيسية)</option>
                @foreach($categories as $cat)
            <option value="{{ $cat->id }}" {{ old('parent_id', $category->parent_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                @endforeach
        </select>        </div>
                    @error('parent_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>
    </div>
    <!-- /.card-body -->
    <div class="card-footer">
      <button type="submit" class="btn btn-info float-right">حفظ التغييرات</button>
    </div>
    <!-- /.card-footer -->
  </form>
</div>
@endsection