@extends('dashboard.layouts.master')
@section('title')
    {{__('messages.manage_medical_specialities')}}
@endsection
@section('content')
    <x-breadcrumb title="{{__('messages.manage_medical_specialities')}}"
                  pagetitle="{{__('messages.medical_specialities')}}"
                  route="{{route('medical-specialities.index')}}"/>
    <div class="d-flex justify-content-sm-end">
        <a href="{{route('medical-specialities.create')}}">
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
                    <th scope="col">{{__('messages.activation')}}</th>
                    <th scope="col">{{__('messages.actions')}}</th>
                </tr>
                </thead>
                <tbody>
                @foreach($resources as $resource)
                    <tr id="role{{$resource->id}}Row">
                        <th scope="row">
                        <span class="fw-semibold text-primary cursor-context-menu">#{{$loop->iteration}}</span>
                        <!-- <a href="#" class="fw-semibold">#{{$loop->iteration}}</a> -->
                        </th>
                        <td>{{$resource->name}}</td>
                        @include('dashboard.partials.__table-actions', ['resource' => $resource, 'route' => 'medical-specialities', 'showModel' => true])
                        @include('dashboard.medical-specialities.show', ['resource' => $resource])
                    </tr>
                @endforeach
                </tbody>
            </table>
            @include('dashboard.layouts.paginate')
        </div>
    </div>
@endsection
