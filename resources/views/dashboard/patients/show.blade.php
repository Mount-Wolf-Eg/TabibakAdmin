@extends('dashboard.layouts.master')
@section('title')
    {{__('messages.patient')}}
@endsection
@section('content')
    <x-breadcrumb title="{{__('messages.patient_details')}}" pagetitle="{{__('messages.patients')}}" route="{{route('patients.index')}}"/>
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <img src="{{$patient->user?->avatar->asset_url ?? asset('assets/images/users/user-dummy-img.jpg')}}" class="card-img-top img-fluid mx-auto d-block float-md-left mr-md-4" @style(['max-height:400px'])>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card p-2">
                <div class="card-body">
                    <h5 class="card-title py-2">{{ucfirst($patient->user?->name)}}</h5>
                    <p class="card-text">{{__('messages.joined')}}: {{$patient->user?->created_at->format('Y-m-d')}}</p>
                    <h5 class="card-title py-2">{{__('messages.details')}}</h5>
                    <div class="row py-2">
                        <div class="col-6">{{__('messages.national_id')}}</div>
                        <div class="col-6">{{$patient->national_id}}</div>
                    </div>
                    <div class="row py-2">
                        <div class="col-6">{{__('messages.phone')}}</div>
                        <div class="col-6">{{$patient->user?->phone}}</div>
                    </div>
                    <div class="row py-2">
                        <div class="col-6">{{__('messages.date_of_birth')}}</div>
                        <div class="col-6">{{$patient->date_of_birth?->format('Y-m-d')}}</div>
                    </div>
                    <div class="row py-2">
                        <div class="col-6">{{__('messages.social_status')}}</div>
                        <div class="col-6">{{$patient->social_status ? : __('messages.no_social_status')}}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
