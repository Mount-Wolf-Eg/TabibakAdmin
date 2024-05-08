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
                            <div class="col-6">{{__('messages.name')}}</div>
                            <div class="col-6">{{$resource->name}}</div>
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
