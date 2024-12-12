@extends('front.layouts.app')
@section('content')

    <!--== Start Page Title Area ==-->
    <section class="page-title-area">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-12 m-auto">
                    <div class="page-title-content text-center">
                        {{ __('auth.welcome_message') }}
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--== End Page Title Area ==-->

    {{-- Register Form --}}
    <div id="register-form" style="display: none;">
        <section class="login-register-area">
            <div class="container">
                <div class="row">
                    <!-- Register Section -->
                    <div class="col-md-8 offset-md-2 login-register-border">
                        <div class="login-register-content login-register-pl" style="direction: rtl;">
                            <div class="login-register-style">
                                <div class="login-register-title mb-30">
                                    <h2>{{ __('auth.register_title') }}</h2>
                                    <p>{{ __('auth.register_description') }}</p>
                                </div>
                                <form action="{{ route('register') }}" method="post">
                                    @csrf
                                    <div class="login-register-input">
                                        <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" placeholder="{{ __('auth.username_placeholder') }}"/>
                                        @error('name', 'registerErrors')
                                        <div style="color: red; font-size: 12px;">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="login-register-input">
                                        <input id="phone" type="text" name="phone" value="{{ old('phone') }}" required autocomplete="phone" placeholder="{{ __('auth.phone_placeholder') }}"/>
                                        @error('phone', 'registerErrors')
                                        <div style="color: red; font-size: 12px;">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="login-register-input">
                                        <input id="email" type="text" name="email" value="{{ old('email') }}"  autocomplete="email" placeholder="{{ __('auth.email_placeholder') }}"/>
                                        @error('email', 'registerErrors')
                                        <div style="color: red; font-size: 12px;">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="login-register-input">
                                        <input id="password" type="password" name="password" required autocomplete="new-password" placeholder="{{ __('auth.password_placeholder') }}"/>
                                        @error('password', 'registerErrors')
                                        <div style="color: red; font-size: 12px;">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="login-register-input">
                                        <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="{{ __('auth.confirm_password_placeholder') }}"/>
                                        @error('password_confirmation', 'registerErrors')
                                        <div style="color: red; font-size: 12px;">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="btn-style-3">
                                        <button class="btn" type="submit"><strong>{{ __('auth.register_button') }}</strong></button>
                                    </div>
                                </form>
                                <div class="text-center mt-3">
                                    <a href="#" id="show-login" class="btn btn-link">{{ __('auth.login_link') }}</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    {{-- Login form --}}
    <div id="login-form">
        <section class="login-register-area">
            <div class="container">
                <div class="row">
                    <!-- Login Section -->
                    <div class="col-md-8 offset-md-2 login-register-border">
                        <div class="login-register-content">
                            <div class="login-register-style login-register-pr" style="direction: rtl;">
                                <div class="login-register-title mb-30">
                                    {{ __('auth.login_welcome') }}
                                    <a href="#" id="show-register" class="btn btn-link no-acount">{{ __('auth.no_account') }}</a>
                                    <h2 class=""><strong>{{ __('auth.login_button') }}</strong></h2>
                                </div>
                                <form id="login-form" action="{{ route('login') }}" method="post">
                                    @csrf
                                    <div class="login-register-input">
                                        <input type="text" name="phone" value="{{ old('phone') }}" placeholder="{{ __('auth.phone_placeholder') }}">
                                        @error('phone')
                                        <div style="color: red; font-size: 12px;">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="login-register-input mt-4">
                                        <input type="password" name="password" placeholder="{{ __('auth.password_placeholder') }}">
                                        @error('password')
                                        <div style="color: red; font-size: 12px;">{{ $message }}</div>
                                        @enderror
                                    </div>
{{--                                    <div class="remember-me-btn">--}}
{{--                                        <input type="checkbox" name="remember">--}}
{{--                                        <label>&nbsp;&nbsp;{{ __('auth.remember_me') }} &nbsp;</label>--}}
{{--                                    </div>--}}
{{--                                    <div class="forgot">--}}
{{--                                        <a href="#">{{ __('auth.forgot_password') }}</a>--}}
{{--                                    </div>--}}
                                    <div class="btn-style-3 mt-4">
                                        <button class="btn" type="submit"><strong>{{ __('auth.login_button') }}</strong></button>
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

@push('styles')
<style>
    .no-acount{
        color:#0074e5 !important;
    }
</style>
@endpush
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
