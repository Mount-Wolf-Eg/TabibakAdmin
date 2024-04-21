@extends('dashboard.layouts.master')
@section('title')
    {{__('messages.manage_users')}}
@endsection
@section('content')
    <x-breadcrumb title="{{__('messages.manage_users')}}" pagetitle="{{__('messages.users')}}" route="{{route('users.index')}}"/>
    <div class="d-flex justify-content-sm-end">
        <a href="{{route('users.create')}}">
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
                    <th scope="col">{{__('translate.name')}}</th>
                    <th scope="col">{{__('translate.email')}}</th>
                    <th scope="col">{{__('translate.phone')}}</th>
                    <th scope="col">{{__('translate.role')}}</th>
                    <th scope="col">{{__('translate.activation')}}</th>
                    <th scope="col">{{__('translate.actions')}}</th>
                </tr>
                </thead>
                <tbody>
                @foreach($resources as $resource)
                    <tr id="user{{$resource->id}}Row">
                        <th scope="row">
                            <a href="#" class="fw-semibold">#{{$loop->iteration}}</a>
                        </th>
                        <td>{{$resource->name}}</td>
                        <td>{{$resource->email}}</td>
                        <td>{{$resource->phone}}</td>
                        <td>
                            @foreach($resource->roles as $role)
                                {{$role->name}}
                            @endforeach
                        </td>
                        @include('dashboard.partials.__table-actions', ['resource' => $resource, 'route' => 'users', 'showModel' => false])
                    </tr>
                @endforeach
                </tbody>
            </table>
            @include('dashboard.layouts.paginate')
        </div>
    </div>
@endsection
