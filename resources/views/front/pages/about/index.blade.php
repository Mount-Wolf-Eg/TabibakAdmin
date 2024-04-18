@extends('front.layouts.master')
@section('title', __('messages.aboutUs'))
@section('content')
    @include('front.partials.__offcanvas')
    @include('front.partials.__breadcrumb', ['title' => __('messages.aboutUs'), 'link' =>  ['text' => __('messages.about'), 'route' => 'front.about']])
@endsection
