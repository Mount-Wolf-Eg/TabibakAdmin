@extends('dashboard.layouts.master')
@section('title')
    {{__('messages.manage_faqs')}}
@endsection
@section('content')
    <x-breadcrumb title="{{__('messages.manage_faqs')}}" pagetitle="{{__('messages.faqs')}}" route="{{route('faqs.index')}}"/>
    <div class="d-flex justify-content-sm-end">
        <a href="{{route('faqs.create')}}">
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
                    <th scope="col">{{__('messages.subject')}}</th>
                    <th scope="col">{{__('messages.question')}}</th>
                    <th scope="col">{{__('messages.creation_date')}}</th>
                    <th scope="col">{{__('messages.activation')}}</th>
                    <th scope="col">{{__('messages.actions')}}</th>
                </tr>
                </thead>
                <tbody>
                @foreach($resources as $resource)
                    <tr id="faq{{$resource->id}}Row">
                        <th scope="row">
                             <span class="fw-semibold text-primary cursor-context-menu">#{{$loop->iteration}}</span>

                            <!-- <a href="#" class="fw-semibold">#{{$loop->iteration}}</a> -->
                        </th>
                        <td>{{$resource->faqSubject->name}}</td>
                        <td>{{$resource->question}}</td>
                        <td>{{$resource->created_at?->format('Y-m-d')}}</td>
                        @include('dashboard.partials.__table-actions', ['resource' => $resource, 'route' => 'faqs', 'showModel' => true])
                        @include('dashboard.faqs.show', ['resource' => $resource])
                    </tr>
                @endforeach
                </tbody>
            </table>
            @include('dashboard.layouts.paginate')
        </div>
    </div>
@endsection
