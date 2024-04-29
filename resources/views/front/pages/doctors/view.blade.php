@extends('front.layouts.master')
@section('title', __('messages.aboutUs'))
@section('content')
    @include('front.partials.__offcanvas')
    @include('front.partials.__breadcrumb', ['title' => __('messages.doctor_details'), 'headerBgClass' => 'bg-1'])
    @include('front.pages.doctors.partials.__details')
@endsection
