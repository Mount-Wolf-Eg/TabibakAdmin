{!! Form::open(['route' => $action, 'method'=> $method]) !!}
<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card">
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-12">
                        {{Form::label('name', __('messages.name'), ['class' => 'form-label'])}}
                        <span class="text-danger fw-bold">*</span>
                        {!! Form::text('name' , $role->name ?? '', ['class' => 'form-control']) !!}
                        @error("name")
                        <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>
                    <div class="offset-6"></div>
                    <div class="col-12">
                        {{Form::label('name', __('messages.permissions'), ['class' => 'form-label'])}}
                        <span class="text-danger fw-bold">*</span>
                    </div>
                    @error("role_permissions")
                        <span class="text-danger">{{$message}}</span>
                    @enderror
                    @foreach($permissionsModules as $module => $permissions)
                        <div class="col-lg-4">
                            <div class="card">
                                <div class="card-header" id="collapsibleCardHeading{{$module}}">
                                    <h5 class="mb-0">
                                        <a href="#" class="btn btn-link" data-bs-toggle="collapse"
                                           data-bs-target="#{{$module}}" aria-expanded="true"
                                           aria-controls="{{$module}}">
                                            {{__('messages.models.' . lcfirst($module))}}
                                        </a>
                                    </h5>
                                </div>
                                <div id="{{$module}}" class="collapse show"
                                     aria-labelledby="collapsibleCardHeading{{$module}}">
                                    <div class="card-body">
                                        <div class="form-check pb-2">
                                            <input type="checkbox" id="selectAll{{$module}}"
                                                   class="form-check-input select-all-checkbox">
                                            <label for="selectAll{{$module}}" class="form-check-label text-primary">{{__('messages.select_all')}}</label>
                                        </div>
                                        @foreach($permissions as $permission)
                                            <div class="form-check">
                                                <input name="role_permissions[]" value="{{$permission->id}}"
                                                       @if(request()->routeIs('roles.edit'))
                                                           @checked(in_array($permission->id, $role->permissions->pluck('id')->toArray() ?? []))
                                                       @endif
                                                       @checked(in_array($permission->id, old('role_permissions') ?? []))
                                                       class="form-check-input permission-checkbox" type="checkbox" id="checkbox{{$permission->id}}">
                                                <label class="form-check-label" for="checkbox{{$permission->id}}">
                                                    {{__('messages.role_permissions.' . $permission->action)}}
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    <div class="col-lg-12">
                        <div class="{{app()->getLocale() == 'ar' ? 'text-start' : 'text-end'}}">
                            <button type="submit" class="btn btn-primary">{{__('messages.save')}}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--end col-->
</div>
{!! Form::close() !!}
