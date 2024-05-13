{!! Form::open(['route' => $action, 'method'=> $method]) !!}
<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card">
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-lg-6">
                        {{Form::label('title', __('messages.title_en'), ['class' => 'form-label'])}}
                        <span class="text-danger fw-bold">*</span>
                        {!! Form::text('title[en]' , isset($featuredList) ? $featuredList->getTranslation('title', 'en') : '', ['class' => 'form-control']) !!}
                        @error("title.en")
                        <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>
                    <div class="col-lg-6">
                        {{Form::label('title', __('messages.title_ar'), ['class' => 'form-label'])}}
                        <span class="text-danger fw-bold">*</span>
                        {!! Form::text('title[ar]' , isset($featuredList) ? $featuredList->getTranslation('title', 'ar') : '', ['class' => 'form-control']) !!}
                        @error("title.ar")
                        <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>
                    <div class="col-lg-6">
                        {{Form::label('text', __('messages.text_en'), ['class' => 'form-label'])}}
                        <span class="text-danger fw-bold">*</span>
                        {!! Form::textarea('text[en]' , isset($featuredList) ? $featuredList->getTranslation('text', 'en') : '', ['class' => 'form-control']) !!}
                        @error("text.en")
                        <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>
                    <div class="col-lg-6">
                        {{Form::label('text', __('messages.text_ar'), ['class' => 'form-label'])}}
                        <span class="text-danger fw-bold">*</span>
                        {!! Form::textarea('text[ar]' , isset($featuredList) ? $featuredList->getTranslation('text', 'ar') : '', ['class' => 'form-control']) !!}
                        @error("text.ar")
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
