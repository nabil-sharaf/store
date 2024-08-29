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

    <!--== Start Login Wrapper ==-->
    <section class="login-register-area">
        <div class="container">
            <div class="row">
                <!-- Login Section -->
                <div class="col-md-8 offset-md-2 login-register-border">
                    <div class="login-register-content">
                        <div class="login-register-style login-register-pr" style="direction: rtl;">
                            <div class="login-register-title mb-30">
                                <h2>تسجيل الدخول</h2>
                                <div style="direction: rtl;">
                                    <p>مرحبا بعودتك مرة أخرى</p>
                                </div>
                            </div>
                            <form action="{{ route('login') }}" method="post">
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


    <!--== End Login Wrapper ==-->
@endsection
