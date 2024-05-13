@extends('dashboard.layouts.master')
@section('title')
    {{__('messages.edit_featured_list')}}
@endsection
@section('content')
    <x-breadcrumb title="{{__('messages.edit_featured_list')}}"
                  pagetitle="{{__('messages.featured_lists')}}" route="{{route('featured-lists.index')}}"/>
    <div class="row">
        <div class="col-md-12">
            @include('dashboard.featured-lists.partials.__form', ['action' => ['featured-lists.update', $featuredList->id], 'method' => 'PUT'])
        </div>
    </div>
@endsection
