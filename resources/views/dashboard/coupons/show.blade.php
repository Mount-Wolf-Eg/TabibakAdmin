{{-- Details Modal --}}
<div class="modal fade detailsModal" id="detailsModal{{$resource->id}}" tabindex="-1" role="dialog" aria-labelledby="detailsModalLabel">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detailsModalLabel">{{__('messages.details')}}</h5>
            </div>
            <div class="modal-body">
                <div class="card p-2">
                    <div class="card-body">
                        <div class="row py-2">
                            <div class="col-6">{{__('messages.code')}}</div>
                            <div class="col-6">{{$resource->code}}</div>
                        </div>
                        <div class="row py-2">
                            <div class="col-6">{{__('messages.discount_type')}}</div>
                            <div class="col-6">{{$resource->discount_type}}</div>
                        </div>
                        <div class="row py-2">
                            <div class="col-6">{{__('messages.discount_amount')}}</div>
                            <div class="col-6">{{$resource->discount_amount}}</div>
                        </div>
                        <div class="row py-2">
                            <div class="col-6">{{__('messages.valid_from')}}</div>
                            <div class="col-6">{{$resource->valid_from?->format('Y-m-d')}}</div>
                        </div>
                        <div class="row py-2">
                            <div class="col-6">{{__('messages.valid_to')}}</div>
                            <div class="col-6">{{$resource->valid_to?->format('Y-m-d')}}</div>
                        </div>
                        <div class="row py-2">
                            <div class="col-6">{{__('messages.description')}}</div>
                            <div class="col-6">{{$resource->description ?  : __('messages.no_description')}}</div>
                        </div>
                        <div class="row py-2">
                            <div class="col-6">{{__('messages.specialities')}}</div>
                            <div class="col-6">
                                @foreach($resource->medicalSpecialities as $speciality)
                                    <span class="d-block">- {{$speciality->name}}</span>
                                @endforeach
                            </div>
                        </div>
                        <div class="row py-2">
                            <div class="col-6">{{__('messages.user_limit')}}</div>
                            <div class="col-6">{{$resource->user_limit}}</div>
                        </div>
                        <div class="row py-2">
                            <div class="col-6">{{__('messages.total_limit')}}</div>
                            <div class="col-6">{{$resource->total_limit}}</div>
                        </div>
                        <div class="row py-2">
                            <div class="col-6">{{__('messages.created')}}</div>
                            <div class="col-6">{{$resource->created_at?->format('Y-m-d')}}</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" onclick="hideModal()">{{__('messages.close')}}</button>
            </div>
        </div>
    </div>
</div>
{{-- Details Modal --}}
