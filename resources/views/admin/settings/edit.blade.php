@extends('admin.layouts.app')

@section('content')
    <div class="container">
        <h2>تحديث الإعدادات</h2>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @php
            $hiddenKeys = [
                'goomla_min_number', // المفاتيح التي تريد التحقق منها
                'goomla_min_prices'
                 ];
            $rolesToHide = ['editor', 'supervisor']; // الأدوار التي تريد إخفاء المفاتيح لها
        @endphp

        <form action="{{ route('admin.settings.update') }}" method="POST">
            @csrf

            @foreach($settings as $setting)
                @php
                    // تعيين قيمة $hideInput افتراضيًا إلى false
                    $hideInput = false;

                    // تحقق مما إذا كان المفتاح الحالي في قائمة المفاتيح المخفية
                    if (in_array($setting->setting_key, $hiddenKeys)) {
                        // تحقق مما إذا كان المستخدم يمتلك أحد الأدوار المحددة
                        if (auth('admin')->user()) {
                            $userRoles = auth('admin')->user()->roles->pluck('name')->toArray();
                            if (array_intersect($userRoles, $rolesToHide)) {
                                $hideInput = true;
                            }
                        }
                    }
                @endphp

                @if (!$hideInput)
                    <div class="form-group">
                        <label for="{{ $setting->setting_key }}">{{ $setting->description ?? $setting->setting_key }}</label>

                        @if($setting->setting_type == 'text')
                            <textarea name="{{ $setting->setting_key }}" class="form-control" rows="3">{{ $setting->setting_value }}</textarea>
                        @else
                            <input type="{{ $setting->setting_type }}" name="{{ $setting->setting_key }}" value="{{ $setting->setting_value }}" class="form-control">
                        @endif
                    </div>
                @endif
            @endforeach

            <button type="submit" class="btn btn-primary">حفظ التعديلات</button>
        </form>
    </div>
@endsection
