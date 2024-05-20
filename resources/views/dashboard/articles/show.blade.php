@extends('dashboard.layouts.master')
@section('title')
    {{__('messages.article')}}
@endsection
@section('content')
    <x-breadcrumb title="{{__('messages.articles_details')}}" pagetitle="{{__('messages.articles')}}" route="{{route('articles.index')}}"/>
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <img src="{{ $article->mainImage->asset_url ?? asset('assets/images/logo-sm.png') }}" class="card-img-top img-fluid mx-auto d-block float-md-left mr-md-4" @style(['max-height:400px'])>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card p-2">
                <div class="card-body">
                    <h5 class="card-title py-2">{{ucfirst($article->title)}}</h5>
                    <p class="card-text">{{__('messages.joined')}}: {{$article->created_at?->format('Y-m-d')}}</p>
                    <h5 class="card-title py-2">{{__('messages.details')}}</h5>
                    <div class="row py-2">
                        <div class="col-6">{{__('messages.author')}}</div>
                        <div class="col-6">{{$article->author->name}}</div>
                    </div>
                    <div class="row py-2">
                        <div class="col-6">{{__('messages.speciality')}}</div>
                        <div class="col-6">{{$article->medicalSpeciality?->name}}</div>
                    </div>
                    <div class="row py-2">
                        <div class="col-6">{{__('messages.publish_date')}}</div>
                        <div class="col-6">{{$article->publish_date ? $article->publish_date?->format('Y-m-d') : __('messages.not_published')}}</div>
                    </div>
                    <div class="row py-2">
                        <div class="col-6">{{__('messages.published_by')}}</div>
                        <div class="col-6">{{$article->publisher ? $article->publisher->name : __('messages.not_published')}}</div>
                    </div>
                    <div class="row py-2">
                        <div class="col-6">{{__('messages.likes')}}</div>
                        <div class="col-6">{{count($article->likes)}}</div>
                    </div>
                    <div class="row py-2">
                        <div class="col-6">{{__('messages.views')}}</div>
                        <div class="col-6">{{$article->views}}</div>
                    </div>
                    <div class="row py-2">
                        <div class="col-6">{{__('messages.reports')}}</div>
                        <div class="col-6">{{$article->complaints->count()}}</div>
                    </div>
                    <div class="row py-2">
                        <div class="col-6">{{__('messages.content')}}</div>
                        <div class="col-6">{{$article->content}}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <h5 class="card-title py-2">{{__('messages.images')}}</h5>
            @foreach($article->images as $index => $image)
                <div class="col-md-3">
                    <div class="card mb-4 shadow-sm">
                        <img src="{{$image->asset_url}}" title="{{$image->name}}" data-index="{{$index}}" class="img-fluid cursor-pointer rounded gallery-image w-100 object-fit-cover" style="max-height: 200px;">
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    @include('dashboard.articles.image-modal')
@endsection
