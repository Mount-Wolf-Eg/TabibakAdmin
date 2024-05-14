@extends('dashboard.layouts.master')
@section('title')
    {{__('messages.edit_featured_list')}}
@endsection
@section('content')
    <x-breadcrumb title="{{__('messages.edit_featured_list')}}" pagetitle="{{__('messages.settings')}}" route="{{route('featured-list.edit')}}"/>
    <div class="row">
        <div class="col-md-12">
            @include('dashboard.featured-list.partials.__form', ['action' => ['featured-list.update'], 'method' => 'PUT'])
        </div>
    </div>
@endsection
