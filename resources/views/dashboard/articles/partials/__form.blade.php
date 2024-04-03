{!! Form::open(['route' => $action, 'method'=> $method, 'enctype' => 'multipart/form-data']) !!}
<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card">
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-lg-12">
                        {{Form::label('speciality', __('messages.speciality'), ['class' => 'form-label'])}}
                        <span class="text-danger fw-bold">*</span>
                        {!! Form::select('medical_speciality_id', $specialities->pluck('name', 'id')->prepend(__('messages.select'), ''),
                            $article->medical_speciality_id ?? '',
                            ['class' => 'form-select']) !!}
                        @error("medical_speciality_id")
                        <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>
                    <div class="col-lg-6">
                        {{Form::label('title', __('messages.title_en'), ['class' => 'form-label'])}}
                        <span class="text-danger fw-bold">*</span>
                        {!! Form::text('title[en]' , isset($article) ? $article->getTranslation('title', 'en') : '', ['class' => 'form-control']) !!}
                        @error("title.en")
                        <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>
                    <div class="col-lg-6">
                        {{Form::label('title', __('messages.title_ar'), ['class' => 'form-label'])}}
                        <span class="text-danger fw-bold">*</span>
                        {!! Form::text('title[ar]' , isset($article) ? $article->getTranslation('title', 'ar') : '', ['class' => 'form-control']) !!}
                        @error("title.ar")
                        <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>
                    <div class="col-lg-6">
                        {{Form::label('content', __('messages.content_en'), ['class' => 'form-label'])}}
                        <span class="text-danger fw-bold">*</span>
                        {!! Form::textarea('content[en]' , isset($article) ? $article->getTranslation('content', 'en') : '', ['class' => 'form-control']) !!}
                        @error("content.en")
                        <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>
                    <div class="col-lg-6">
                        {{Form::label('content', __('messages.content_ar'), ['class' => 'form-label'])}}
                        <span class="text-danger fw-bold">*</span>
                        {!! Form::textarea('content[ar]' , isset($article) ? $article->getTranslation('content', 'ar') : '', ['class' => 'form-control']) !!}
                        @error("content.ar")
                        <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>
                    <div class="col-md-6">
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
                                        <h5 class="card-title mb-1 d-inline">{{__('messages.main_image')}}</h5>
                                        @if(request()->routeIs('articles.create'))
                                            <span class="text-danger fw-bold">*</span>
                                        @endif
                                        <p class="text-muted mb-0">{{__('messages.upload') . ' ' . __('messages.main_image')}}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body pt-2 pb-3">
                                {!! Form::file('main_image', ['class' => 'form-control', 'accept' => 'image/jpeg, image/png', 'value' => old('main_image')]) !!}
                                @error("main_image")
                                <span class="text-danger">{{$message}}</span>
                                @enderror
                                <br>
                                @if(isset($article) && $article->mainImage)
                                    <div class="col-6 position-relative">
                                        <a class="btn btn-flat-light my-3 mx-2 remove-image-resource position-absolute top-0 {{app()->getLocale() == 'ar' ? 'start' : 'end'}}-0" data-id="{{$article->mainImage->id}}">
                                            <i class="bi bi-x-lg"></i>
                                        </a>
                                        <img src="{{$article->mainImage->asset_url}}" title="{{$article->mainImage->name}}" class="img-fluid mt-3" alt="{{__('messages.main_image')}}" style="max-height: 200px">
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
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
                                        <h5 class="card-title mb-1 d-inline">{{__('messages.images')}}</h5>
                                        <p class="text-muted mb-0">{{__('messages.upload') . ' ' . __('messages.images')}}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body pt-2 pb-3">
                                {!! Form::file('images[]', ['class' => 'form-control', 'accept' => 'image/jpeg, image/png', 'value' => old('images'), 'multiple']) !!}
                                @error("images")
                                <span class="text-danger">{{$message}}</span>
                                @enderror
                                <br>
                                @if(isset($article) && $article->images)
                                    <div class="row">
                                        @foreach($article->images  as $index => $image)
                                            <div class="col-6 position-relative">
                                                <a class="btn btn-flat-light my-3 mx-2 remove-image-resource position-absolute top-0 {{app()->getLocale() == 'ar' ? 'start' : 'end'}}-0" data-id="{{$image->id}}">
                                                    <i class="bi bi-x-lg"></i>
                                                </a>
                                                <img src="{{$image->asset_url}}" title="{{$image->name}}" data-index="{{$index}}" class="img-fluid cursor-pointer gallery-image mt-3" alt="{{$image->name}}" style="max-height: 200px">
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>
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
<form class="d-inline" method="POST" id="removeImageForm">
    @csrf
    @method('DELETE')
</form>
