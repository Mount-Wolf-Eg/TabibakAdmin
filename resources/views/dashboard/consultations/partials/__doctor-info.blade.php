<div class="card">
    <div class="card-header">
        <h5>
            <i class="bi bi-person-circle mx-2"></i>
            {{__('messages.doctor_info')}}
        </h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-lg-4">
                <p class="my-2"><strong>{{__('messages.name')}}</strong></p>
            </div>
            <div class="col-lg-8">
                <img src="{{$consultation->doctor->user?->avatar_asset_default_url}}" alt="avatar" class="img-thumbnail rounded-circle" style="width: 50px; height: 50px;">
                <p class="d-inline">{{$consultation->doctor->user?->name}}</p>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4">
                <p><strong>{{__('messages.age')}}</strong></p>
            </div>
            <div class="col-lg-8">
                <p>{{$consultation->doctor->user?->patient->age}}</p>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4">
                <p><strong>{{__('messages.phone')}}</strong></p>
            </div>
            <div class="col-lg-8">
                <p>{{$consultation->doctor->user?->phone}}</p>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4">
                <p><strong>{{__('messages.academic_degree')}}</strong></p>
            </div>
            <div class="col-lg-8">
                <p>{{$consultation->doctor?->academicDegree?->name}}</p>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4">
                <p><strong>{{__('messages.request_id')}}</strong></p>
            </div>
            <div class="col-lg-8">
                <p>#{{$consultation->id}}</p>
            </div>
        </div>
    </div>
</div>
