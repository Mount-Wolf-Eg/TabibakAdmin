{!! Form::open(['route' => $action, 'method' => $method, 'enctype' => 'multipart/form-data']) !!}
<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card">
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-12">
                        {{Form::label('name', __('messages.name'), ['class' => 'form-label'])}}
                        <span class="text-danger fw-bold">*</span>
                        {!! Form::text('name' , $user->name, ['class' => 'form-control']) !!}
                        @error("name")
                        <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>
                    <div class="col-lg-6">
                        {{Form::label('email', __('messages.email'), ['class' => 'form-label'])}}
                        <span class="text-danger fw-bold">*</span>
                        {!! Form::email('email' , $user->email, ['class' => 'form-control']) !!}
                        @error("email")
                        <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>
                    <div class="col-lg-6">
                        {{Form::label('phone', __('messages.phone'), ['class' => 'form-label'])}}
                        <span class="text-danger fw-bold">*</span>
                        {!! Form::number('phone' , $user->phone, ['class' => 'form-control', 'pattern' => '[0-9]', 'onkeypress' => 'return isNumberKey(event)']) !!}
                        @error("phone")
                        <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>
                    @if($user->vendor)
                        <input type="hidden" name="vendor_profile" value="true">
                        <div class="col-lg-6">
                            {{Form::label('services', __('messages.services'), ['class' => 'form-label'])}}
                            <span class="text-danger fw-bold">*</span>
                            {!! Form::select('services[]' ,$services->pluck('name', 'id'),
                                $user->vendor?->vendorServices->pluck('id'),
                                ['class' => 'form-select', 'multiple' => true]) !!}
                            @error("services")
                            <span class="text-danger">{{$message}}</span>
                            @enderror
                            @error("services.*")
                            <span class="text-danger">{{$message}}</span>
                            @enderror
                        </div>
                        <div class="col-lg-6">
                            {{Form::label('address', __('messages.address'), ['class' => 'form-label'])}}
                            {!! Form::text('address' , $user->vendor?->address ?? '', ['class' => 'form-control']) !!}
                            @error("address")
                            <span class="text-danger">{{$message}}</span>
                            @enderror
                        </div>
                    @endif
                    <div class="col-lg-6">
                        <div class="card">
                            <div class="card-header">
                                <div class="d-flex">
                                    <div class="flex-shrink-0 {{app()->getLocale() == 'ar' ? 'ms-3' : 'me-3'}}">
                                        <div class="avatar-sm">
                                            <div class="avatar-title rounded-circle bg-light text-primary fs-20">
                                                <i class="bi bi-images"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h5 class="card-title mb-1">{{__('messages.profile_image')}}</h5>
                                        <p class="text-muted mb-0">{{__('messages.upload') . ' ' . __('messages.profile_image')}}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body pt-2 pb-3">
                                {!! Form::file('image', ['class' => 'form-control', 'accept' => 'image/jpeg, image/png', 'value' => old('image')]) !!}
                                @error("image")
                                <span class="text-danger">{{$message}}</span>
                                @enderror
                                <br>
                                @if(isset($user) && $user->avatar)
                                    <div class="col-6 position-relative">
                                        <a class="btn btn-flat-light my-3 mx-2 remove-image-resource position-absolute top-0 {{app()->getLocale() == 'ar' ? 'start' : 'end'}}-0" data-id="{{$user->avatar->id}}">
                                            <i class="bi bi-x-lg"></i>
                                        </a>
                                        <img src="{{$user->avatar->asset_url}}" title="{{$user->avatar->name}}" class="img-fluid mt-3" alt="" style="max-height: 200px">
                                    </div>
                                @endif
                            </div>
                        </div>
                        <!-- end card -->
                    </div>
                    <div class="col-12">
                        <div class="{{app()->getLocale() == 'ar' ? 'text-start' : 'text-end'}}">
                            <button type="submit" class="btn btn-primary">{{__('messages.save')}}</button>
                            <a href="{{route('change-password')}}" class="btn btn-success">{{__('messages.change_password')}}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--end col-->
    </div>
</div>
{!! Form::close() !!}
<form class="d-inline" method="POST" id="removeImageForm">
    @csrf
    @method('DELETE')
</form>
