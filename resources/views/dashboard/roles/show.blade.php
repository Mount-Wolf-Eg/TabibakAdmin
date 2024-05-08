@extends('dashboard.layouts.master')
@section('title')
    {{__('messages.role')}}
@endsection
@section('content')
    <x-breadcrumb title="{{__('messages.role_details')}}" pagetitle="{{__('messages.roles')}}" route="{{route('roles.index')}}"/>
    <div class="row">
        <div class="col-12">
            <div class="card p-2">
                <div class="card-body">
                    <div class="py-2">
                        <h5 class="card-title">{{ucfirst($role->name)}}</h5>
                        <p class="card-text">{{__('messages.created')}}: {{$role->created_at?->format('Y-m-d')}}</p>
                    </div>
                    <div class="py-2">
                        <h5 class="card-title">{{__('messages.permissions')}}</h5>
                    </div>
                    <div class="row">
                        @foreach($role->permissions->groupBy('model') as $module)
                            <div class="col-lg-4">
                                <div class="card">
                                    <div class="card-header" id="collapsibleCardHeading{{$module['0']['model']}}">
                                        <h5 class="mb-0">
                                            <a href="#" class="btn btn-link" data-bs-toggle="collapse"
                                               data-bs-target="#{{$module['0']['model']}}" aria-expanded="true"
                                               aria-controls="{{$module['0']['model']}}">
                                                {{__('messages.models.' . lcfirst($module[0]['model']))}}
                                            </a>
                                        </h5>
                                    </div>
                                    <div id="{{$module['0']['model']}}" class="collapse show"
                                         aria-labelledby="collapsibleCardHeading{{$module['0']['model']}}"  @style(['min-height:143px;'])>
                                        <div class="card-body">
                                            @foreach($module as $permission)
                                                <span class="card-text d-block">{{__('messages.role_permissions.' . $permission->action)}}</span>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
