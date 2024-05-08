@extends('dashboard.layouts.master')
@section('title')
    {{__('messages.user')}}
@endsection
@section('content')
    <x-breadcrumb title="{{__('messages.user_details')}}" pagetitle="{{__('messages.users')}}" route="{{route('users.index')}}"/>
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <img src="{{$user->avatar->asset_url ?? asset('assets/images/users/user-dummy-img.jpg')}}" class="card-img-top img-fluid mx-auto d-block float-md-left mr-md-4" @style(['max-height:400px'])>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card p-2">
                <div class="card-body">
                    <h5 class="card-title py-2">{{ucfirst($user->name)}}</h5>
                    <p class="card-text">{{__('messages.joined')}}: {{$user->created_at?->format('Y-m-d')}}</p>
                    <h5 class="card-title py-2">{{__('messages.details')}}</h5>
                    <div class="row py-2">
                        <div class="col-6">{{__('messages.email')}}</div>
                        <div class="col-6">{{$user->email}}</div>
                    </div>
                    <div class="row py-2">
                        <div class="col-6">{{__('messages.phone')}}</div>
                        <div class="col-6">{{$user->phone}}</div>
                    </div>
                    <div class="row py-2">
                        <div class="col-6">{{__('messages.role')}}</div>
                        <div class="col-6">
                            @foreach($user->roles as $role)
                                {{$role->name}}
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
