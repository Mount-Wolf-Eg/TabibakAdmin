@extends('front.layouts.master')
@section('title', __('messages.aboutUs'))
@section('content')
    @include('front.partials.__offcanvas')
    @include('front.partials.__breadcrumb', ['title' => __('messages.ourDoctors'), 'headerBgClass' => 'bg-1'])
    @include('front.partials.__team')
@endsection
