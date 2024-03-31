@extends('dashboard.layouts.master')
@section('title')
    {{__('messages.consultation')}}
@endsection
@section('content')
    <x-breadcrumb title="{{__('messages.consultation_details')}}" pagetitle="{{__('messages.consultations')}}" route="{{route('consultations.index')}}"/>
    <div class="row">
        <div class="col-md-12">
            <div class="card p-2">
                <div class="card-body">
                    <div class="py-2">
                        <div class="row py-2">
                            <div class="col-6 col-md-4">{{__('messages.doctor')}}</div>
                            <div class="col-6 col-md-8">{{$consultation->doctor?->user->name}}</div>
                        </div>
                        <div class="row py-2">
                            <div class="col-6 col-md-4">{{__('messages.patient')}}</div>
                            <div class="col-6 col-md-8">{{$consultation->patient->user->name}}</div>
                        </div>
                        <div class="row py-2">
                            <div class="col-6 col-md-4">{{__('messages.speciality')}}</div>
                            <div class="col-6 col-md-8">{{$consultation->medicalSpeciality->name}}</div>
                        </div>
                        <div class="row py-2">
                            <div class="col-6 col-md-4">{{__('messages.type')}}</div>
                            <div class="col-6 col-md-8">{{ucfirst(strtolower($consultation->type->name))}}</div>
                        </div>
                        <div class="row py-2">
                            <div class="col-6 col-md-4">{{__('messages.contact_type')}}</div>
                            <div class="col-6 col-md-8">{{ucfirst(strtolower($consultation->contact_type->name))}}</div>
                        </div>
                        <div class="row py-2">
                            <div class="col-6 col-md-4">{{__('messages.status')}}</div>
                            <div class="col-6 col-md-8">{{ucfirst(strtolower($consultation->status->name))}}</div>
                        </div>
                        <div class="row py-2">
                            <div class="col-6 col-md-4">{{__('messages.created')}}</div>
                            <div class="col-6 col-md-8">{{date_format($consultation->created_at, 'd-m-Y')}}</div>
                        </div>
                    </div>
                    <div class="py-2">
                        <h5 class="card-title py-2">{{__('messages.payment')}}</h5>
                        <div class="row py-2">
                            <div class="col-6 col-md-4">{{__('messages.payment_type')}}</div>
                            <div class="col-6 col-md-8">{{ucfirst(strtolower($consultation->payment_type->name))}}</div>
                        </div>
                        <div class="row py-2">
                            <div class="col-6 col-md-4">{{__('messages.amount')}}</div>
                            <div class="col-6 col-md-8">{{$consultation->amount}}</div>
                        </div>
                        <div class="row py-2">
                            <div class="col-6 col-md-4">{{__('messages.coupon')}}</div>
                            <div class="col-6 col-md-8">{{$consultation->coupon->code ?? '-'}}</div>
                        </div>
                    </div>
                    @if($consultation->transfer_case_rate)
                        <div class="py-2">
                            <h5 class="card-title py-2">{{__('messages.transferred')}}</h5>
                            <div class="row py-2">
                                <div class="col-6 col-md-4">{{__('messages.transfer_reason')}}</div>
                                <div class="col-6 col-md-8">{{$consultation->transfer_reason}}</div>
                            </div>
                            <div class="row py-2">
                                <div class="col-6 col-md-4">{{__('messages.transfer_notes')}}</div>
                                <div class="col-6 col-md-8">{{$consultation->transfer_notes}}</div>
                            </div>
                            <div class="row py-2">
                                <div class="col-6 col-md-4">{{__('messages.transfer_case_rate')}}</div>
                                <div class="col-6 col-md-8">{{ucfirst(strtolower($consultation->transfer_case_rate->name))}}</div>
                            </div>
                        </div>
                    @endif
                    <div class="py-2">
                        <h5 class="card-title py-2">{{__('messages.case_description')}}</h5>
                        @if($consultation->patient_description)
                            <div class="row py-2">
                                <div class="col-6 col-md-4">{{__('messages.patient_description')}}</div>
                                <div class="col-6 col-md-8">{{$consultation->patient_description}}</div>
                            </div>
                        @endif
                        @if($consultation->doctor_description)
                            <div class="row py-2">
                                <div class="col-6 col-md-4">{{__('messages.doctor_description')}}</div>
                                <div class="col-6 col-md-8">{{$consultation->doctor_description}}</div>
                            </div>
                        @endif
                    </div>
                    <div class="py-2">
                        <h5 class="card-title py-2">{{__('messages.consultation_result')}}</h5>
                        <div class="row py-2">
                            <div class="col-6 col-md-4">{{__('messages.medical_review')}}</div>
                            <div class="col-6 col-md-8">{{$consultation->medical_review}}</div>
                        </div>
                        <div class="row py-2">
                            <div class="col-6 col-md-4">{{__('messages.prescription')}}</div>
                            <div class="col-6 col-md-8">{{$consultation->prescription}}</div>
                        </div>
                    </div>
                    <div class="py-2">
                        <h5 class="card-title py-2">{{__('messages.diseases')}}</h5>
                        <div class="row py-2">
                            <div class="col-6 col-md-4">{{__('messages.diseases')}}</div>
                            <div class="col-6 col-md-8">
                                @foreach($consultation->diseases as $disease)
                                    <span class="d-block">- {{$disease->name}}</span>
                                @endforeach
                            </div>
                        </div>
                        @if($consultation->other_diseases)
                            <div class="row py-2">
                                <div class="col-6 col-md-4">{{__('messages.other_diseases')}}</div>
                                <div class="col-6 col-md-8">{{$consultation->other_diseases}}</div>
                            </div>
                        @endif
                    </div>
                    <div class="py-2">
                        <h5 class="card-title py-2">{{__('messages.attachments')}}</h5>
                        <div class="row py-2">
                            @foreach($consultation->attachments as $attachment)
                                <div class="col-6 col-md-4">{{$attachment->name}}</div>
                                <div class="col-6 col-md-8">
                                    <span class="px-2 fs-5"><a target="_blank" href="{{ asset($attachment->asset_url) }}"><i class="bi bi-eye"></i></a></span>
                                </div>
                            @endforeach
                            @include('dashboard.consultations.partials.__upload-attachments')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
