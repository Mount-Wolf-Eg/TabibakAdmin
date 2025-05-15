@extends('dashboard.layouts.master')
@section('title')
    {{__('messages.manage_roles')}}
@endsection
@section('content')
    <x-breadcrumb title="{{__('messages.manage_roles')}}" pagetitle="{{__('messages.roles')}}"
                  route="{{route('roles.index')}}"/>
    <div class="d-flex justify-content-sm-end">
        <a href="{{route('roles.create')}}">
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
                            <a href="{{route('roles.show', $resource->id)}}" class="fw-semibold">#{{$loop->iteration}} </a>
                        </th>
                        <td>{{$resource->name}}</td>
                        @include('dashboard.partials.__table-actions', ['resource' => $resource, 'route' => 'roles',
                            'showModel' => false, 'disableEdit' => !$resource->can_be_deleted,
                            'disableDelete' => !$resource->can_be_deleted, 'disableActive' => !$resource->can_be_deleted])
                    </tr>
                @endforeach
                </tbody>
            </table>
            @include('dashboard.layouts.paginate')
        </div>
    </div>
@endsection
