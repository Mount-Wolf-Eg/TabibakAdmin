@extends('front.layouts.master')
@section('title', __('messages.contactUs'))
@section('content')
    @include('front.partials.__offcanvas')
    @include('front.partials.__breadcrumb', ['title' => __('messages.contactUs'), 'link' => ['text' => __('messages.contact'), 'route' => 'front.contact']])
    @include('front.pages.contact.partials.__contact-form')
@endsection
