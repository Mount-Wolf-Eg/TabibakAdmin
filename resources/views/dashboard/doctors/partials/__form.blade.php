{!! Form::open(['route' => $action, 'method'=> $method]) !!}
<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card">
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-lg-6">
                        {{Form::label('name', __('messages.name'), ['class' => 'form-label'])}}
                        <span class="text-danger fw-bold">*</span>
                        {!! Form::text('name' , $doctor->user?->name ?? '', ['class' => 'form-control']) !!}
                        @error("name")
                        <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>
                    <div class="col-lg-6">
                        {{Form::label('academic_degree', __('messages.academic_degree'), ['class' => 'form-label'])}}
                        <span class="text-danger fw-bold">*</span>
                        {!! Form::select('academic_degree_id', $academicDegrees->pluck('name', 'id')->prepend(__('messages.select') , ''),
                            $doctor->academic_degree_id ?? '',
                            ['class' => 'form-select']) !!}
                        @error("academic_degree_id")
                        <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>
                    <div class="col-lg-6">
                        {{Form::label('specialities', __('messages.specialities'), ['class' => 'form-label'])}}
                        <span class="text-danger fw-bold">*</span>
                        {!! Form::select('specialities[]' ,$specialities->pluck('name', 'id'),
                            isset($doctor) ? $doctor->medicalSpecialities->pluck('id') : [],
                            ['class' => 'form-select', 'multiple' => true]) !!}
                        @error("specialities")
                        <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>
                    <div class="col-lg-6">
                        {{Form::label('consultation_types', __('messages.consultation_types'), ['class' => 'form-label'])}}
                        <div class="card">
                            <div class="collapse show"
                                 aria-labelledby="collapsibleCardHeading">
                                <div class="card-body">
                                    <div class="form-check">
                                        <input name="with_appointment_consultation_enabled"
                                               @checked((isset($doctor) && $doctor->with_appointment_consultation_enabled) || old('with_appointment_consultation_enabled') ?? false)
                                               class="form-check-input" type="checkbox">
                                        <label class="form-check-label" for="with_appointment_consultation_enabled">
                                            {{__('messages.scheduled')}}
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input name="urgent_consultation_enabled"
                                               @checked((isset($doctor) && $doctor->urgent_consultation_enabled) || old('urgent_consultation_enabled') ?? false)
                                               class="form-check-input" type="checkbox">
                                        <label class="form-check-label" for="urgent_consultation_enabled">
                                            {{__('messages.urgent')}}
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @error("with_appointment_consultation_enabled")
                        <span class="text-danger">{{$message}}</span>
                        @enderror
                        @error("urgent_consultation_enabled")
                        <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>
                    <div class="col-lg-6">
                        {{Form::label('email', __('messages.email'), ['class' => 'form-label'])}}
                        {!! Form::email('email' , $doctor->user?->email ?? '', ['class' => 'form-control']) !!}
                        @error("email")
                        <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>
                    <div class="col-lg-6">
                        {{Form::label('phone', __('messages.phone'), ['class' => 'form-label'])}}
                        <span class="text-danger fw-bold">*</span>
                        {!! Form::number('phone' , $doctor->user?->phone ?? '', ['class' => 'form-control', 'pattern' => '[0-9]', 'onkeypress' => 'return isNumberKey(event)']) !!}
                        @error("phone")
                        <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>
                    <div class="col-lg-6">
                        {{Form::label('national_id', __('messages.national_id'), ['class' => 'form-label'])}}
                        {!! Form::number('national_id' , $doctor->national_id ?? '', ['class' => 'form-control', 'pattern' => '[0-9]', 'onkeypress' => 'return isNumberKey(event)']) !!}
                        @error("national_id")
                        <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>
                    <div class="col-lg-6">
                        {{Form::label('medical_id', __('messages.medical_id'), ['class' => 'form-label'])}}
                        {!! Form::number('medical_id' , $doctor->medical_id ?? '', ['class' => 'form-control', 'pattern' => '[0-9]', 'onkeypress' => 'return isNumberKey(event)']) !!}
                        @error("medical_id")
                        <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>
                    <div class="col-lg-6">
                        {{Form::label('password', __('messages.password'), ['class' => 'form-label'])}}
                        @if(request()->routeIs('doctors.create'))
                            <span class="text-danger fw-bold">*</span>
                        @endif
                        {!! Form::password('password' , ['class' => 'form-control', 'placeholder' => __('messages.enter_strong_pass')]) !!}
                        <button class="btn btn-link position-absolute {{app()->getLocale() == 'ar' ? 'start-0' : 'end-0'}} text-muted password-addon" style="top:30px"
                                type="button" onclick="togglePasswordVisibility()"><i id="eyeIcon" class="bi bi-eye"></i></button>
                        @error("password")
                        <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>
                    <div class="col-lg-6">
                        {{Form::label('password_confirmation', __('messages.password_confirm'), ['class' => 'form-label'])}}
                        @if(request()->routeIs('doctors.create'))
                            <span class="text-danger fw-bold">*</span>
                        @endif
                        {!! Form::password('password_confirmation' , ['class' => 'form-control', 'placeholder' => __('messages.enter_strong_pass')]) !!}
                        <button class="btn btn-link position-absolute {{app()->getLocale() == 'ar' ? 'start-0' : 'end-0'}} text-muted password-addon" style="top:30px"
                                type="button" onclick="toggleConfirmPasswordVisibility()"><i id="eyeConfirmIcon" class="bi bi-eye"></i></button>
                        @error("password_confirmation")
                        <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>
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
