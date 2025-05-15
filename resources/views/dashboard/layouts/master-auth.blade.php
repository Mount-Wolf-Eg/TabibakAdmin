<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
      dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}"
      data-layout="vertical" data-topbar="brand"
      data-sidebar="gradient"
      data-sidebar-size="lg" data-sidebar-image="none" data-preloader="disable" data-body-image="none"
      data-bs-theme="light">

<head>
    <meta charset="utf-8">
    <title>@yield('title') | {{config('app.name')}} {{__('messages.dashboard')}}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Mount Wolf Information Technology" name="description">
    <meta content="{{config('app.name')}}" name="author">
    <link rel="shortcut icon" href="{{ URL::asset('assets/images/tabibakLogoDark.png') }}">
    @include('dashboard.layouts.styles')
</head>

<body>
<section
    class="auth-page-wrapper position-relative bg-light min-vh-100 d-flex align-items-center justify-content-between">
    <div class="auth-header position-fixed top-0 start-0 end-0 bg-body">
        <div class="container-fluid">
            <div class="row justify-content-between align-items-center">
                <div class="col-2">
                    <a class="navbar-brand mb-2 mb-sm-0" href="{{route('front.home')}}">
                        <img src="{{ URL::asset('assets/images/logo-dark.png') }}" class="card-logo card-logo-dark"
                             alt="logo dark" height="22">
                        <img src="{{ URL::asset('assets/images/logo-light.png') }}"
                             class="card-logo card-logo-light" alt="" height="22">
                    </a>
                </div>
                <div class="col-auto">
                </div>
            </div>
        </div>
    </div>
    @yield('content')
</section>
@yield('scripts')
@include('dashboard.layouts.scripts')
</body>

</html>
