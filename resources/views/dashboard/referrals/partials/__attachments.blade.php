<div class="card">
    <div class="card-header">
        <h5>
            <i class="bi bi-files mx-2"></i>
            {{__('messages.attachments')}}
        </h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>{{__('messages.url')}}</th>
                        <th>{{__('messages.uploader')}}</th>
                        <th>{{__('messages.date')}}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($consultation->attachments as $attachment)
                        <tr>
                            <td>{{$loop->iteration}}</td>
                            <td>
                                <a target="_blank" href="{{ asset($attachment->asset_url) }}" class="text-decoration-underline">
                                    {{$attachment->name}}
                                </a>
                            </td>
                            <td>{{$attachment->user?->name}}</td>
                            <td>{{$attachment->created_at->format('Y-m-d h:i A')}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <div class="col-md-6">
                @include('dashboard.referrals.partials.__upload-attachments')
            </div>
        </div>
    </div>
</div>

