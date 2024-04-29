<!-- All css plugins here -->
<link rel="stylesheet" href="{{ URL::asset('assets/css/bootstrap.min.css') }}" type="text/css">
<link rel="stylesheet" href="{{ URL::asset('assets/css/icons.min.css') }}" type="text/css">
<link rel="stylesheet" href="{{ URL::asset('assets/css/ionicons.min.css') }}" type="text/css">
<link rel="stylesheet" href="{{ URL::asset('assets/css/animate.css') }}" type="text/css">
<link rel="stylesheet" href="{{ URL::asset('assets/css/owl.carousel.min.css') }}" type="text/css">
<link rel="stylesheet" href="{{ URL::asset('assets/css/magnific-popup.css') }}" type="text/css">
<link rel="stylesheet" href="{{ URL::asset('assets/css/meanmenu.css') }}" type="text/css">
<link rel="stylesheet" href="{{ URL::asset('assets/css/global.css') }}" type="text/css">
<link rel="stylesheet" href="{{ URL::asset('assets/css/style.css') }}" type="text/css">
<link rel="stylesheet" href="{{ URL::asset('assets/css/responsive.css') }}" type="text/css">

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v6.1.1/css/all.css">

@if (app()->getLocale() == 'ar')
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;700&display=swap" rel="stylesheet">
    <style>
        * {
            font-family: 'Cairo', sans-serif;
        }
    </style>
@endif
@stack('styles')
