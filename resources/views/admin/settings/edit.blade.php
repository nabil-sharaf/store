@extends('admin.layouts.app')

@section('content')
    <div class="container">
        <h2>تحديث الإعدادات</h2>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('admin.settings.update') }}" method="POST">
            @csrf

            @foreach($settings as $setting)
                <div class="form-group">
                    <label for="{{ $setting->setting_key }}">{{ $setting->description ?? $setting->setting_key }}</label>

                    @if($setting->setting_type == 'text')
                        <textarea name="{{ $setting->setting_key }}" class="form-control" rows="3">{{ $setting->setting_value }}</textarea>
                    @else
                        <input type="{{ $setting->setting_type }}" name="{{ $setting->setting_key }}" value="{{ $setting->setting_value }}" class="form-control">
                    @endif
                </div>
            @endforeach

            <button type="submit" class="btn btn-primary">حفظ التعديلات</button>
        </form>
    </div>
@endsection
