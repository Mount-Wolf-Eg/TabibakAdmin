@extends('dashboard.layouts.master')
@section('title')
    {{__('messages.manage_articles')}}
@endsection
@section('content')
    <x-breadcrumb title="{{__('messages.manage_articles')}}" pagetitle="{{__('messages.articles')}}" route="{{route('articles.index')}}"/>
    <div class="d-flex justify-content-sm-end">
        <a href="{{route('articles.create')}}">
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
                    <th scope="col">{{__('messages.author')}}</th>
                    <th scope="col">{{__('messages.publish_date')}}</th>
                    <th scope="col">{{__('messages.likes')}}</th>
                    <th scope="col">{{__('messages.views')}}</th>
                    <th scope="col">{{__('messages.reports')}}</th>
                    <th scope="col">{{__('messages.publish')}}</th>
                    <th scope="col">{{__('messages.actions')}}</th>
                </tr>
                </thead>
                <tbody>
                @foreach($resources as $resource)
                    <tr id="article{{$resource->id}}Row">
                        <th scope="row">
                            <a href="#" class="fw-semibold">#{{$loop->iteration}}</a>
                        </th>
                        <td>{{$resource->title}}</td>
                        <td>{{$resource->author->name}}</td>
                        <td>{{$resource->publish_date ? $resource->publish_date?->format('Y-m-d') : __('messages.not_published')}}</td>
                        <td>{{count($resource->likes)}}</td>
                        <td>{{$resource->views}}</td>
                        <td>{{$resource->complaints->count()}}</td>
                        <td>
                            <div class="form-check form-switch d-inline">
                                <input class="form-check-input publish-resource" type="checkbox" data-id="{{$resource->id}}" data-activation="{{$resource->publish_date ? 1 : 0}}" data-publish="{{$resource->publish_date}}"
                                    @checked($resource->publish_date)>
                            </div>
                            <form action="{{route("articles.publish", $resource->id)}}" method="POST" id="publishResourceForm-{{$resource->id}}">
                                @csrf
                                @method('PUT')
                            </form>
                        </td>
                        @include('dashboard.partials.__table-actions', ['resource' => $resource, 'route' => 'articles', 'showModel' => false, 'hideActive' => true])
                    </tr>
                @endforeach
                </tbody>
            </table>
            @include('dashboard.layouts.paginate')
        </div>
    </div>

    @push('scripts')
        <script>
            $('.publish-resource').on('change', function(e) {
                e.preventDefault();
                let id = $(this).data('id');
                let publish = $(this).data('publish');
                let activation = $(this).data('activation');
                let text = '';
                if (publish) {
                    text = 'You want to inpublish this Article!';
                } else {
                    text = 'You want to publish this Article!';
                }
                Swal.fire({
                    title: 'Are you sure?',
                    text: text,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#2a4fd7',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes'
                }).then((result) => {
                    if (result.isConfirmed && result.value) {
                        $('#publishResourceForm-'+id).submit();
                    } else {
                        if (activation === 1) {
                            $(this).prop('checked', true);
                        } else {
                            $(this).prop('checked', false);
                        }
                    }
                })
            })
        </script>
    @endpush
@endsection
