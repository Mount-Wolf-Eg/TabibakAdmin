@extends('dashboard.layouts.master')
@section('title')
    {{__('messages.manage_contacts')}}
@endsection
@section('content')
    <x-breadcrumb title="{{__('messages.manage_contacts')}}" pagetitle="{{__('messages.contacts')}}"
                  route="{{route('contact.index')}}"/>
    <x-filter/>
    <div class="row">
        <div class="col-12">
            <table class="table table-nowrap">
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">{{__('messages.name')}}</th>
                    <th scope="col">{{__('messages.email')}}</th>
                    <th scope="col">{{__('messages.message')}}</th>
                    <th scope="col">{{__('messages.details')}}</th>
                </tr>
                </thead>
                <tbody>
                @foreach($resources as $resource)
                    <tr id="contact{{$resource->id}}Row">
                        <th scope="row">
                        <span class="fw-semibold text-primary cursor-context-menu">#{{$loop->iteration}}</span>

                            <!-- <a href="#" class="fw-semibold">#{{$loop->iteration}}</a> -->
                        </th>
                        <td>{{$resource->name}}</td>
                        <td>{{$resource->email}}</td>
                        <td>{{$resource->message}}</td>
                        <td><a href="{{route('contact.show', $resource->id)}}" class="btn btn-primary">{{__('messages.details')}}</a></td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            @include('dashboard.layouts.paginate')
        </div>
    </div>
@endsection
