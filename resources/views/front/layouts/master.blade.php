<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
      dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}"
      data-layout="vertical" data-topbar="brand"
      data-sidebar="gradient"
      data-sidebar-size="lg" data-sidebar-image="none" data-preloader="disable" data-body-image="none"
      data-bs-theme="light">

<head>
    <meta charset="utf-8">
    <title>@yield('title') | {{config('app.name')}}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{csrf_token()}}">
    <meta content="eCommerce + Admin HTML Template" name="description">
    <meta content="{{config('app.name')}}" name="author">
    <link rel="shortcut icon" href="{{ URL::asset('assets/images/tabibakLogoDark.png') }}">

	@include('front.layouts.styles')
    <script src="{{asset('assets/js/frontend/modernizr-2.8.3.min.js')}}"></script>
</head>

<body>
	@include('front.layouts.header')
	<div class="body-overlay"></div>

	@yield('content')

	@include('front.layouts.footer')
    @include('front.layouts.scripts')
</body>

</html>
