@extends('dashboard.layouts.master')
@section('title')
    {{__('messages.manage_payments')}}
@endsection
@section('content')
    <x-breadcrumb title="{{__('messages.manage_payments')}}"
                  pagetitle="{{__('messages.payments')}}"
                  route="{{route('consultations.index')}}"/>
    <x-filter>
        <div class="col-lg-2 py-1">
            {{ Form::label('from', __('messages.from'), ['class' => 'form-label']) }}
            {!! Form::date('fromCreationDate' , request('fromCreationDate'), ['class' => 'form-control']) !!}
        </div>
        <div class="col-lg-2 py-1">
            {{ Form::label('to', __('messages.to'), ['class' => 'form-label']) }}
            {!! Form::date('toCreationDate' , request('toCreationDate'), ['class' => 'form-control']) !!}
        </div>
        <div class="col-lg-2 py-1">
            {{ Form::label('patients', __('messages.patients'), ['class' => 'form-label']) }}
            {!! Form::select('payer' , $patients->pluck('user.name', 'user.id')->prepend(__('messages.select'), ''),
                request('payer'),  ['class' => 'form-control select2']) !!}
        </div>
        <div class="col-lg-2 py-1">
            {{ Form::label('statuses', __('messages.status'), ['class' => 'form-label']) }}
            {!! Form::select('status' , $statuses->pluck('txt', 'value')->prepend(__('messages.select'), ''),
                request('status'),  ['class' => 'form-control select2']) !!}
        </div>
        <div class="col-lg-2 py-1">
            {{ Form::label('paymentMethod', __('messages.payment_method'), ['class' => 'form-label']) }}
            {!! Form::select('paymentMethod' , $methods->pluck('txt', 'value')->prepend(__('messages.select'), ''),
                request('paymentMethod'),  ['class' => 'form-control select2']) !!}
        </div>
    </x-filter>
    <div class="row">
        <div class="col-12">
            <table class="table table-nowrap">
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">{{__('messages.request_id')}}</th>
                    <th scope="col">{{__('messages.date')}}</th>
                    <th scope="col">{{__('messages.doctor_name')}}</th>
                    <th scope="col">{{__('messages.patient_name')}}</th>
                    <th scope="col">{{__('messages.transaction_id')}}</th>
                    <th scope="col">{{__('messages.amount')}}</th>
                    <th scope="col">{{__('messages.payment_method')}}</th>
                    <th scope="col">{{__('messages.status')}}</th>
                </tr>
                </thead>
                <tbody>
                @foreach($resources as $resource)
                    <tr id="role{{$resource->id}}Row">
                        <th scope="row">
                            <a href="#" class="fw-semibold">#{{$loop->iteration}}</a>
                        </th>
                        <td><a href="{{route('consultations.show', $resource->payable->id)}}">#{{$resource->payable->id}}</a></td>
                        <td>{{$resource->created_at->format('Y-m-d h:i A')}}</td>
                        <td>{{$resource->payable->doctor?->user->name}}</td>
                        <td>{{$resource->beneficiary->name}}</td>
                        <td>{{$resource->transaction_id}}</td>
                        <td>{{$resource->amount . ' ' . $resource->currency->symbol}}</td>
                        <td>{{$resource->payment_method->label()}}</td>
                        <td><span class="text-{{$resource->status->colorClass()}}">{{$resource->status->label()}}</span></td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            @include('dashboard.layouts.paginate')
        </div>
    </div>
@endsection
