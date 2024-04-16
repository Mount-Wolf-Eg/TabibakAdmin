@extends('front.layouts.master')
@section('title', __('messages.aboutUs'))
@section('content')
    @include('front.partials.__offcanvas')
    @include('front.partials.__breadcrumb', ['title' => __('messages.aboutUs'), 'link' => __('messages.about')])
    @include('front.partials.__about')
    @include('front.pages.about.partials.__expertise')
    @include('front.pages.about.partials.__counter')
    @include('front.partials.__team')
    @include('front.partials.__suggestions')
    @include('front.partials.__clients')
@endsection
