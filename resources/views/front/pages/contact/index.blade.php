@extends('front.layouts.master')
@section('title', __('messages.contactUs'))
@section('content')
    @include('front.partials.__offcanvas')
    @include('front.partials.__breadcrumb', ['title' => __('messages.contactUs'), 'link' => __('messages.contact')])
    @include('front.pages.contact.partials.__contact')
    @include('front.pages.contact.partials.__map')
    @include('front.pages.contact.partials.__contact-form')
@endsection
