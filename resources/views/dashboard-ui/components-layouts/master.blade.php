<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-layout="vertical" data-topbar="light"
      data-sidebar="light"
      data-sidebar-size="lg" data-sidebar-image="none" data-preloader="disable" data-body-image="none">

<head>
    <meta charset="utf-8">
    <title>@yield('title') | Toner eCommerce + Admin HTML Template</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="eCommerce + Admin HTML Template" name="description">
    <meta content="Themesbrand" name="author">
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ URL::asset('assets/images/tabibakLogoDark.png') }}">

    <!-- head css -->
    @include('dashboard-ui.components-layouts.head-css')
</head>

<body>

<!-- Begin page -->
<div id="layout-wrapper">
    <!-- top tagbar -->
    @include('dashboard-ui.components-layouts.top-tagbar')
    <!-- topbar -->
    @include('dashboard-ui.components-layouts.topbar')
    @include('dashboard-ui.components-layouts.sidebar')

    <!-- ============================================================== -->
    <!-- Start right Content here -->
    <!-- ============================================================== -->
    <div class="main-content">

        <div class="page-content">
            <div class="container-fluid">
                @yield('content')
            </div>
            <!-- container-fluid -->
        </div>
        <!-- End Page-content -->

        <!-- footer -->
        @include('dashboard-ui.components-layouts.footer')
    </div>
    <!-- end main content-->

</div>
<!-- END layout-wrapper -->

<!-- customizer -->
@include('dashboard-ui.components-layouts.customizer')
<!-- scripts -->
@include('dashboard-ui.components-layouts.vendor-scripts')

</body>

</html>
