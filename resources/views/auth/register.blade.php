@extends('front.layouts.app')
@section('content')

    <!--== Start Page Title Area ==-->
    <section class="page-title-area">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-12 m-auto">
                    <div class="page-title-content text-center">
                        مرحبا بك سجل دخولك الشخصي
                        او انشئ حسابك الجديد وتمتع بكل مزايا عملائنا
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--== End Page Title Area ==-->

{{--register Form--}}
    <div id="register-form" style="display: none;">
        <section class="login-register-area">
            <div class="container">
                <div class="row">
                    <!-- Register Section -->
                    <div class="col-md-8 offset-md-2 login-register-border">
                        <div class="login-register-content login-register-pl" style="direction: rtl;">
                            <div class="login-register-style">
                                <div class="login-register-title mb-30">
                                    <h2>تسجيل حساب جديد</h2>
                                    <p>ليس لديك حساب سجل الان وتمتع بكل مزايا وخصومات العملاء المميزين</p>
                                </div>
                                <form action="{{ route('register') }}" method="post">
                                    @csrf
                                    <div class="login-register-input">
                                        <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" placeholder="اسم المستخدم"/>
                                        @error('name', 'registerErrors')
                                        <div style="color: red; font-size: 12px;">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="login-register-input">
                                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="البريد الالكتروني"/>
                                        @error('email', 'registerErrors')
                                        <div style="color: red; font-size: 12px;">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="login-register-input">
                                        <input id="password" type="password" name="password" required autocomplete="new-password" placeholder="كلمة السر"/>
                                        @error('password', 'registerErrors')
                                        <div style="color: red; font-size: 12px;">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="login-register-input">
                                        <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="تأكيد كلمة السر"/>
                                        @error('password_confirmation', 'registerErrors')
                                        <div style="color: red; font-size: 12px;">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="btn-style-3">
                                        <button class="btn" type="submit"><strong>تسجيل</strong></button>
                                    </div>
                                </form>
                                <div class="text-center mt-3">
                                    <a href="#" id="show-login" class="btn btn-link">لديك حساب بالفعل؟ سجل الدخول</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>


{{--    login form--}}
    <div id="login-form">
        <section class="login-register-area">
            <div class="container">
                <div class="row">
                    <!-- Login Section -->
                    <div class="col-md-8 offset-md-2 login-register-border">
                        <div class="login-register-content">
                            <div class="login-register-style login-register-pr" style="direction: rtl;">
                                <div class="login-register-title mb-30">
                                   مرحبا بعودتك مرة أخرى
                                         <a href="#" id="show-register" class="btn btn-link">ليس لديك حساب؟ سجل الآن</a>
                                    <h2 class=""><strong>تسجيل الدخول</strong></h2>
                                </div>
                                <form id="login-form" action="{{ route('login') }}" method="post">
                                    @csrf
                                    <div class="login-register-input">
                                        <input type="text" name="email" value="{{ old('email') }}" placeholder="البريد الالكتروني">
                                        @error('email', 'loginErrors')
                                        <div style="color: red; font-size: 12px;">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="login-register-input">
                                        <input type="password" name="password" placeholder="كلمة المرور">
                                        @error('password', 'loginErrors')
                                        <div style="color: red; font-size: 12px;">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="remember-me-btn">
                                        <input type="checkbox" name="remember">
                                        <label>&nbsp;&nbsp;تذكرني &nbsp;</label>
                                    </div>
                                    <div class="forgot">
                                        <a href="#">! forget password</a>
                                    </div>
                                    <div class="btn-style-3">
                                        <button class="btn" type="submit"><strong>تسجيل دخول</strong></button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>


@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#show-register').click(function(e) {
                e.preventDefault();
                $('#login-form').fadeOut(function() {
                    $('#register-form').fadeIn();
                });
            });

            $('#show-login').click(function(e) {
                e.preventDefault();
                $('#register-form').fadeOut(function() {
                    $('#login-form').fadeIn();
                });
            });
        });
    </script>
@endpush
