{!! Form::open(['route' => 'notes.store', 'method'=> 'POST']) !!}
<div class="modal fade" id="noteForm" tabindex="-1" aria-labelledby="noteFormLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="noteFormLabel">{{__('messages.add_note')}}</h5>
                <button type="button" class="btn btn-flat-light close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><i class="bi bi-x-lg"></i></span>
                </button>
            </div>
            <div class="modal-body">
                {!! Form::hidden('notable_id', $consultation->id) !!}
                {!! Form::hidden('notable_type', 'Consultation') !!}
                <div class="col-lg-12">
                    {!! Form::textarea('text' , old('text'), ['class' => 'form-control', 'required' => 'required', 'id' => 'note', 'maxlength' => 500]) !!}
                    <div id="noteTextCounter" class="text-end"></div>
                    @error("text")
                        <span class="text-danger">{{$message}}</span>
                    @enderror
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary close" data-bs-dismiss="modal">{{__('messages.close')}}</button>
                <button type="submit" class="btn btn-primary">{{__('messages.save')}}</button>
            </div>
        </div>
    </div>
</div>
{!! Form::close() !!}
@push('scripts')
    <script>
        $(document).ready(function() {
            const textarea = $("#note");
            const charCount = $("#noteTextCounter");
            let currentLength = textarea.val().length;
            charCount.text(`${currentLength}/500`);
            textarea.on("input", () => {
                currentLength = textarea.val().length;
                charCount.text(`${currentLength}/500`);
            });
            $(".close").on("click", function(e) {
                textarea.val("");
                charCount.text("0/500");
            });
        });
    </script>
@endpush
