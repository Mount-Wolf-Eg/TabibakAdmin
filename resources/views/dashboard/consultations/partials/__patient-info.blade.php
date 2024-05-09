<div class="card">
    <div class="card-header">
        <h5>
            <i class="bi bi-person-circle mx-2"></i>
            {{__('messages.patient_info')}}
        </h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-lg-4">
                <p class="my-2"><strong>{{__('messages.name')}}</strong></p>
            </div>
            <div class="col-lg-8">
                <img src="{{$consultation->patient?->user?->avatar_asset_default_url}}" alt="avatar" class="img-thumbnail rounded-circle" style="width: 50px; height: 50px;">
                <p class="d-inline">{{$consultation->patient?->user?->name}}</p>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4">
                <p><strong>{{__('messages.speciality')}}</strong></p>
            </div>
            <div class="col-lg-8">
                <p>{{$consultation->medicalSpeciality?->name}}</p>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4">
                <p><strong>{{__('messages.phone')}}</strong></p>
            </div>
            <div class="col-lg-8">
                <p>{{$consultation->patient?->user?->phone}}</p>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4">
                <p><strong>{{__('messages.reporting_date')}}</strong></p>
            </div>
            <div class="col-lg-8">
                <p>{{$consultation->created_at->format('Y-m-d h:i A')}}</p>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4">
                <p><strong>{{__('messages.referral_reason')}}</strong></p>
            </div>
            <div class="col-lg-8">
                <p>{{$consultation->transfer_reason}}</p>
            </div>
        </div>
    </div>
</div>
