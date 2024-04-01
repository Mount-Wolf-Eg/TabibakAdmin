{!! Form::open(['route' => $action, 'method' => $method]) !!}
<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card">
            <div class="card-body">
                <div class="row g-3">
                    <div class="position-relative col-12">
                        {{Form::label('old_password', __('messages.old_password'), ['class' => 'form-label'])}}
                        <span class="text-danger fw-bold">*</span>
                        {!! Form::password('old_password' , ['class' => 'form-control', 'placeholder' => __('messages.enter_old_password')]) !!}
                        <button class="btn btn-link position-absolute {{app()->getLocale() == 'ar' ? 'start-0' : 'end-0'}} text-muted password-addon" style="top:30px"
                                type="button" onclick="toggleOldPasswordVisibility()"><i id="eyeOldIcon" class="bi bi-eye"></i></button>
                        @error("old_password")
                        <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>
                    <div class="position-relative col-12">
                        {{Form::label('password', __('messages.new_password'), ['class' => 'form-label'])}}
                        <span class="text-danger fw-bold">*</span>
                        {!! Form::password('password' , ['class' => 'form-control', 'placeholder' => __('messages.enter_strong_pass')]) !!}
                        <button class="btn btn-link position-absolute {{app()->getLocale() == 'ar' ? 'start-0' : 'end-0'}} text-muted password-addon" style="top:30px"
                                type="button" onclick="togglePasswordVisibility()"><i id="eyeIcon" class="bi bi-eye"></i></button>
                        @error("password")
                        <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>
                    <div class="col-12">
                        {{Form::label('password_confirmation', __('messages.new_password_confirm'), ['class' => 'form-label'])}}
                        <span class="text-danger fw-bold">*</span>
                        {!! Form::password('password_confirmation' , ['class' => 'form-control', 'placeholder' => __('messages.enter_strong_pass')]) !!}
                        <button class="btn btn-link position-absolute {{app()->getLocale() == 'ar' ? 'start-0' : 'end-0'}} text-muted password-addon" style="top:30px"
                                type="button" onclick="toggleConfirmPasswordVisibility()"><i id="eyeConfirmIcon" class="bi bi-eye"></i></button>
                        @error("password_confirmation")
                        <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>
                    <div class="col-12">
                        <div class="{{app()->getLocale() == 'ar' ? 'text-start' : 'text-end'}}">
                            <button type="submit" class="btn btn-primary">{{__('messages.save')}}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--end col-->
    </div>
{!! Form::close() !!}
