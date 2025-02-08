@extends('dashboard.layouts.master')
@section('title')
    {{__('messages.manage_referrals')}}
@endsection
@section('content')
    <x-breadcrumb title="{{__('messages.manage_referrals')}}"
                  pagetitle="{{__('messages.referrals')}}"
                  route="{{route('consultations.index')}}"/>
    <x-filter>
        <div class="col-lg-2 py-1">
            {{ Form::label('Date', __('messages.date'), ['class' => 'form-label']) }}
            {!! Form::date('creationDate' , request('creationDate'), ['class' => 'form-control']) !!}
        </div>
        <div class="col-lg-2 py-1">
            {{ Form::label('doctor', __('messages.doctor'), ['class' => 'form-label']) }}
            {!! Form::select('doctor' , $doctors->pluck('user.name', 'id')->prepend(__('messages.select'), ''),
                request('doctor'),  ['class' => 'form-control select2']) !!}
        </div>
        <div class="col-lg-2 py-1">
            {{ Form::label('urgency_levels', __('messages.urgency_levels'), ['class' => 'form-label']) }}
            {!! Form::select('type' , $types->pluck('txt', 'value')->prepend(__('messages.select'), ''),
                request('type'),  ['class' => 'form-control select2']) !!}
        </div>
        @if(auth()->user()->vendor)
            <div class="col-lg-2 py-1">
                {{ Form::label('status', __('messages.status'), ['class' => 'form-label']) }}
                {!! Form::select('myVendorStatus' , $vendorStatuses->pluck('txt', 'value')->prepend(__('messages.select'), ''),
                    request('myVendorStatus'),  ['class' => 'form-control select2']) !!}
            </div>
        @endif
    </x-filter>
    <div class="row">
        <div class="col-12">
            <table class="table table-nowrap">
                <thead>
                <tr>
                     <th scope="col">{{__('messages.request_id')}}</th>
                     <th scope="col">{{__('messages.doctor_name')}}</th>
                     <th scope="col">{{__('messages.refer_to_doctor')}}</th>
                     <th scope="col">{{__('messages.referral_reason')}}</th>
                     <!-- <th scope="col">{{__('messages.doctor_phone')}}</th> -->
                    <th scope="col">{{__('messages.reporting_date')}}</th>
                    <th scope="col">{{__('messages.actions')}}</th>
                    <th scope="col">{{__('messages.session_status')}}</th>


                    <!-- <th scope="col">{{__('messages.patient_name')}}</th> -->
                    <!-- <th scope="col">{{__('messages.urgency_level')}}</th> -->
                    @if(auth()->user()?->vendor)
                        <th scope="col">{{__('messages.vendor_status')}}</th>
                        <th scope="col">{{__('messages.request_actions')}}</th>
                    @endif
                </tr>
                </thead>
                <tbody>
                @foreach($resources as $resource)
                    <tr id="role{{$resource->id}}Row">
                       
                        <td><a href="{{route('consultations.show', $resource->id)}}">#{{$resource->id}}</a></td>
                        <td>{{$resource->doctor?->user?->name}}</td>
                        <td>refer to doctor</td>
                        <td>{{$resource->transfer_reason}}</td>
                        <!-- <td>{{$resource->doctor?->user?->phone}}</td> -->
                        <td>{{$resource->created_at->format('Y-m-d h:i A')}}</td>
                        @include('dashboard.partials.__table-actions', ['resource' => $resource, 'disableEdit' => true,
                        'disableDelete' => !auth()->user()->can('delete-consultation'),
                        'route' => 'consultations', 'hideActive' => true, 'showModel' => false])
                    
                        <td>session status</td>
                    
                        
                        <td>{{$resource->patient?->user?->name}}</td>
                        <td>{{$resource->type?->label()}}</td>
                        @if(auth()->user()?->vendor)
                            <td><span
                                    class="text-{{$resource->getVendorStatusColor(auth()->user()->vendor->id)}}">{{$resource->getVendorStatusTxt(auth()->user()->vendor->id)}}
                            </td>
                            <td>
                                @include('dashboard.consultations.partials.__vendor-actions')
                            </td>
                        @endif
                    </tr>
                @endforeach
                </tbody>
            </table>
            @include('dashboard.layouts.paginate')
        </div>
    </div>
@endsection
