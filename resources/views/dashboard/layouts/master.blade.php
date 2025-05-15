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
    <meta name="csrf-token" content="{{csrf_token()}}">
    <meta content="eCommerce + Admin HTML Template" name="description">
    <meta content="{{config('app.name')}}" name="author">
    <link rel="shortcut icon" href="{{ URL::asset('assets/images/tabibakLogoDark.png') }}">
    @include('dashboard.layouts.styles')
</head>

<body>

<!-- Begin page -->
<div id="layout-wrapper">
    @include('dashboard.layouts.navbar')
    @include('dashboard.layouts.sidebar')
    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                @yield('content')
            </div>
        </div>
        @include('dashboard.layouts.footer')
    </div>
</div>
@include('dashboard.layouts.scripts')
</body>
</html>
