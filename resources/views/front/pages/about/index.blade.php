@extends('front.layouts.master')
@section('title', __('messages.aboutUs'))
@section('content')
    @include('front.partials.__offcanvas')
    @include('front.partials.__breadcrumb', ['title' => __('messages.aboutUs'), 'headerBgClass' => 'bg-3'])
    @include('front.pages.about.partials.__about-us')
@endsection
