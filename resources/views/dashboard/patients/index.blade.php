@extends('dashboard.layouts.master')
@section('title')
    {{__('messages.manage_patients')}}
@endsection
@section('content')
    <x-breadcrumb title="{{__('messages.manage_patients')}}"
                  pagetitle="{{__('messages.patients')}}"
                  route="{{route('patients.index')}}"/>
    <div class="d-flex justify-content-sm-end">
        <a href="{{route('patients.create')}}">
            <i class="bi bi-plus-circle"></i>
            {{__('messages.add_new')}}
        </a>
    </div>
    <x-filter/>
    <div class="row">
        <div class="col-12">
            <table class="table table-nowrap">
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">{{__('messages.name')}}</th>
                    <th scope="col">{{__('messages.national_id')}}</th>
                    <th scope="col">{{__('messages.phone')}}</th>
                    <th scope="col">{{__('messages.activation')}}</th>
                    <th scope="col">{{__('messages.actions')}}</th>
                </tr>
                </thead>
                <tbody>
                @foreach($resources as $resource)
                    <tr id="role{{$resource->id}}Row">
                        <th scope="row">
                            <a href="#" class="fw-semibold">#{{$loop->iteration}}</a>
                        </th>
                        <td>{{$resource->user?->name}}</td>
                        <td>{{$resource->national_id}}</td>
                        <td>{{$resource->user?->phone}}</td>
                        @include('dashboard.partials.__table-actions', ['resource' => $resource, 'route' => 'patients', 'showModel' => false])
                    </tr>
                @endforeach
                </tbody>
            </table>
            @include('dashboard.layouts.paginate')
        </div>
    </div>
@endsection
