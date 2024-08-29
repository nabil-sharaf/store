@include('front.layouts.head')
@include('front.layouts.main_header')

<main class="main-content">
@yield('content')
</main>

@include('front.layouts.footer')
@include('front.layouts.aside_menu')
@include('front.layouts.footer_scripts')
