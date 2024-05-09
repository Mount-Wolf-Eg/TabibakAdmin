@extends('dashboard.layouts.master')
@section('title')
    {{__('messages.edit_user')}}
@endsection
@section('content')
    <x-breadcrumb title="{{__('messages.edit_user')}}" pagetitle="{{__('messages.users')}}" route="{{route('users.index')}}"/>
    <div class="row">
        <div class="col-md-12">
            @include('dashboard.users.partials.__form', ['action' => ['users.update', $user?->id], 'method' => 'PUT'])
        </div>
    </div>
@endsection
