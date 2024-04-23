@extends('front.layouts.master')
@section('title', __('messages.home'))
@section('content')
    @include('front.partials.__offcanvas')
	@include('front.pages.home.partials.__slider')
	@include('front.pages.home.partials.__about')
	@include('front.pages.home.partials.__department')
	@include('front.partials.__team')
	@include('front.pages.home.partials.__video')
    @include('front.pages.home.partials.__counter')
	@include('front.pages.home.partials.__suggestions')
	{{-- @include('front.pages.home.partials.__news') --}}
	@include('front.partials.__clients')
@endsection
