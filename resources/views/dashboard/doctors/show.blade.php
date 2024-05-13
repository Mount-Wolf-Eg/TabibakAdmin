@extends('dashboard.layouts.master')
@section('title')
    {{__('messages.doctor')}}
@endsection
@section('content')
    <x-breadcrumb title="{{__('messages.doctor_details')}}" pagetitle="{{__('messages.doctors')}}" route="{{route('doctors.index')}}"/>
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <img src="{{$doctor->user?->avatar->asset_url ?? asset('assets/images/users/user-dummy-img.jpg')}}" class="card-img-top img-fluid mx-auto d-block float-md-left mr-md-4" @style(['max-height:400px'])>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card p-2">
                <div class="card-body">
                    <div class="py-2">
                        <h5 class="card-title py-2">{{ucfirst($doctor->user?->name)}}</h5>
                        <p class="card-text">{{__('messages.joined')}}: {{$doctor->user?->created_at->format('Y-m-d')}}</p>
                    </div>
                    <div class="py-2">
                        <h5 class="card-title py-2">{{__('messages.bio')}}</h5>
                        <p class="card-text">{{$doctor->bio ? : __('messages.no_bio')}}</p>
                    </div>
                    <div class="py-2">
                        <h5 class="card-title py-2">{{__('messages.personal_details')}}</h5>
                        <div class="row py-2">
                            <div class="col-6">{{__('messages.national_id')}}</div>
                            <div class="col-6">{{$doctor->national_id}}</div>
                        </div>
                        <div class="row py-2">
                            <div class="col-6">{{__('messages.email')}}</div>
                            <div class="col-6">{{$doctor->user?->email}}</div>
                        </div>
                        <div class="row pt-2">
                            <div class="col-6">{{__('messages.phone')}}</div>
                            <div class="col-6">{{$doctor->user?->phone}}</div>
                        </div>
                    </div>
                    <div class="py-2">
                        <h5 class="card-title py-2">{{__('messages.professional_details')}}</h5>
                        <div class="row py-2">
                            <div class="col-6">{{__('messages.medical_id')}}</div>
                            <div class="col-6">{{$doctor->medical_id}}</div>
                        </div>
                        <div class="row py-2">
                            <div class="col-6">{{__('messages.academic_degree')}}</div>
                            <div class="col-6">{{$doctor->academicDegree->name}}</div>
                        </div>
                        <div class="row py-2">
                            <div class="col-6">{{__('messages.university')}}</div>
                            <div class="col-6">{{$doctor->university ? : __('messages.no_university')}}</div>
                        </div>
                        <div class="row py-2">
                            <div class="col-6">{{__('messages.specialities')}}</div>
                            <div class="col-6">
                                @foreach($doctor->medicalSpecialities as $speciality)
                                    <span class="d-block">- {{$speciality->name}}</span>
                                @endforeach
                            </div>
                        </div>
                        <div class="row py-2">
                            <div class="col-6">{{__('messages.experience_years')}}</div>
                            <div class="col-6">{{$doctor->experience_years ? : 0}}</div>
                        </div>
                        <div class="row py-2">
                            <div class="col-6">{{__('messages.accept_requests')}}</div>
                            <div class="col-6">{{$doctor->request_status ? __('messages.active') : __('messages.deactivate')}}</div>
                        </div>
                        <div class="row py-2">
                            <div class="col-6">{{__('messages.consultation_types')}}</div>
                            <div class="col-6">
                                {{($doctor->with_appointment_consultation_enabled ? __('messages.scheduled') : '-') . ' / ' . ($doctor->urgent_consultation_enabled ? __('messages.urgent') : '-')}}
                            </div>
                        </div>
                    </div>
                    <div class="py-2">
                        <h5 class="card-title py-2">{{__('messages.attachments')}}</h5>
                        <div class="row py-2">
                            @foreach($doctor->attachments as $attachment)
                                <div class="col-6 col-md-4">{{$attachment->name}}</div>
                                <div class="col-6 col-md-8">
                                    <span class="px-2 fs-5"><a href="{{ asset($attachment->asset_url) }}"><i class="bi bi-eye"></i></a></span>
                                    <span class="px-2 fs-5"><a href="{{route('download', ['dir' => 'doctors', 'file_name' => $attachment->name])}}" class="text-success"><i class="bi bi-download"></i></a></span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
