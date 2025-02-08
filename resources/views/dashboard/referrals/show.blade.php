@extends('dashboard.layouts.master')
@section('title')
    {{__('messages.referral')}}
@endsection
@section('content')
    <x-breadcrumb title="{{__('messages.referral_details')}}" pagetitle="{{__('messages.referrals')}}" route="{{route('consultations.index')}}"/>
    <div class="row">
        <div class="col-lg-6">
            @include('dashboard.consultations.partials.__patient-info')
        </div>
        <div class="col-lg-6 text-right">
            @include('dashboard.consultations.partials.__doctor-info')
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h5>
                        <i class="bi bi-list-nested mx-2"></i>
                        {{__('messages.doctor_notes_recommendations')}}
                    </h5>
                </div>
                <div class="card-body">
                    <p>{{$consultation->doctor_description}}</p>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            @include('dashboard.consultations.partials.__medicines')
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            @include('dashboard.consultations.partials.__attachments')
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            @include('dashboard.consultations.partials.__notes')
        </div>
    </div>
    @if(auth()->user()?->vendor)
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="d-inline">
                            <i class="bi bi-text-paragraph mx-2"></i>
                            {{__('messages.actions')}}
                        </h5>
                    </div>
                    <div class="card-body">
                        @include('dashboard.consultations.partials.__vendor-actions' ,['resource' => $consultation])
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection
