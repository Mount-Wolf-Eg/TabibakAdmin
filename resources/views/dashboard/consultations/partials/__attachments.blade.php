<div class="py-2">
    <h5 class="card-title py-2">{{__('messages.attachments')}}</h5>
    <div class="row py-2">
        @foreach($consultation->attachments as $attachment)
            <div class="col-6 col-md-4">{{$attachment->name}}</div>
            <div class="col-6 col-md-8">
                <span class="px-2 fs-5"><a target="_blank" href="{{ asset($attachment->asset_url) }}"><i class="bi bi-eye"></i></a></span>
            </div>
        @endforeach
        @include('dashboard.consultations.partials.__upload-attachments')
    </div>
</div>
