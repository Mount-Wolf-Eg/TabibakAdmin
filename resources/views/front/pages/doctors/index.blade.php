@extends('front.layouts.master')
@section('title', __('messages.aboutUs'))
@section('content')
    @include('front.partials.__offcanvas')
    @include('front.partials.__breadcrumb', ['title' => __('messages.our_doctors'), 'link' => ['text' => __('messages.doctors'), 'route' => 'front.doctors']])
    @include('front.pages.doctors.partials.__team')
@endsection
