@extends('dashboard.layouts.master')
@section('title')
    {{__('messages.profile')}}
@endsection
@section('content')
    <x-breadcrumb title="{{__('messages.profile')}}" pagetitle="{{__('messages.tabibak')}}" route="{{route('dashboard')}}"/>
    <div class="row">
        <div class="col-md-12">
            @include('dashboard.profile.partials.__form', ['action' => ['users.update', $user->id], 'method' => 'PUT'])
        </div>
    </div>
@endsection
