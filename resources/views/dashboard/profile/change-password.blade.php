@extends('dashboard.layouts.master')
@section('title')
    {{__('messages.change_password')}}
@endsection
@section('content')
    <x-breadcrumb title="{{__('messages.change_password')}}" pagetitle="{{__('messages.profile')}}" route="{{route('profile')}}"/>
    <div class="row">
        <div class="col-md-12">
            @include('dashboard.profile.partials.__change-password-form', ['action' => ['update-password'], 'method' => 'POST'])
        </div>
    </div>
@endsection
