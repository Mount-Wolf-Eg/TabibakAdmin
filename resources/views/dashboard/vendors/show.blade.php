@extends('dashboard.layouts.master')
@section('title')
    {{__('messages.vendor')}}
@endsection
@section('content')
    <x-breadcrumb title="{{__('messages.vendor_details')}}" pagetitle="{{__('messages.vendors')}}" route="{{route('vendors.index')}}"/>
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <img src="{{$vendor->user->avatar->asset_url ?? asset('assets/images/users/user-dummy-img.jpg')}}" class="card-img-top img-fluid mx-auto d-block float-md-left mr-md-4" @style(['max-height:400px'])>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card p-2">
                <div class="card-body">
                    <h5 class="card-title py-2">{{ucfirst($vendor->user->name)}}</h5>
                    <p class="card-text">{{__('messages.joined')}}: {{$vendor->user?->created_at->format('Y-m-d')}}</p>
                    <h5 class="card-title py-2">{{__('messages.details')}}</h5>
                    <div class="row py-2">
                        <div class="col-6">{{__('messages.email')}}</div>
                        <div class="col-6">{{$vendor->user->email}}</div>
                    </div>
                    <div class="row py-2">
                        <div class="col-6">{{__('messages.phone')}}</div>
                        <div class="col-6">{{$vendor->user->phone}}</div>
                    </div>
                    <div class="row py-2">
                        <div class="col-6">{{__('messages.type')}}</div>
                        <div class="col-6">{{$vendor->vendorType->name}}</div>
                    </div>
                    <div class="row py-2">
                        <div class="col-6">{{__('messages.address')}}</div>
                        <div class="col-6">{{$vendor->address ? : __('messages.no_address')}}</div>
                    </div>
                    <div class="row py-2">
                        <div class="col-6">{{__('messages.services')}}</div>
                        <div class="col-6">
                            @foreach($vendor->vendorServices as $service)
                                <span class="d-block">- {{$service->name}}</span>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
