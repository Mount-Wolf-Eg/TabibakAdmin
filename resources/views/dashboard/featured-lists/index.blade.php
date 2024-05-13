@extends('dashboard.layouts.master')
@section('title')
    {{__('messages.manage_featured_lists')}}
@endsection
@section('content')
    <x-breadcrumb title="{{__('messages.manage_featured_lists')}}"
                  pagetitle="{{__('messages.featured_lists')}}"
                  route="{{route('featured-lists.index')}}"/>
    <div class="d-flex justify-content-sm-end">
        <a href="{{route('featured-lists.create')}}">
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
                    <th scope="col">{{__('messages.title')}}</th>
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
                        <td>{{$resource->title}}</td>
                        @include('dashboard.partials.__table-actions', ['resource' => $resource, 'route' => 'featured-lists', 'showModel' => true])
                        @include('dashboard.featured-lists.show', ['resource' => $resource])
                    </tr>
                @endforeach
                </tbody>
            </table>
            @include('dashboard.layouts.paginate')
        </div>
    </div>
@endsection
