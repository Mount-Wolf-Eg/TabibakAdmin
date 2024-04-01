{!! Form::open(['route' => 'files.store', 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}
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
                <h5 class="card-title mb-1">{{__('messages.attachments')}}</h5>
                <p class="text-muted mb-0">{{__('messages.upload') . ' ' . __('messages.attachments')}}</p>
            </div>
        </div>
    </div>
    <div class="card-body pt-2 pb-3">
        {!! Form::file('file', ['class' => 'form-control', 'accept' => 'image/jpeg, image/png', 'value' => old('image')]) !!}
        <input type="hidden" name="fileable_id" value="{{$consultation->id}}">
        <input type="hidden" name="type"
               value="{{\App\Constants\FileConstants::FILE_TYPE_CONSULTATION_ATTACHMENTS->value}}">
        @error("file")
        <span class="text-danger">{{$message}}</span>
        @enderror
    </div>
    <div class="card-footer">
        <button type="submit" class="btn btn-primary">{{__('messages.upload')}}</button>
    </div>
</div>
{!! Form::close() !!}
