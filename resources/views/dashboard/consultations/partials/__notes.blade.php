<div class="py-2">
    <h5 class="card-title">
        <span class="fw-semibold">
            {{__('messages.notes')}}
        </span>
        <button type="button" class="btn btn-primary btn-sm float-end" data-bs-toggle="modal"
                data-bs-target="#noteForm">
            <i class="bi bi-plus"></i>
            {{__('messages.add_note')}}
        </button>
        @include('dashboard.consultations.partials.__note-form')
    </h5>
    <div class="row py-2">
        <div class="col-12">
            <table class="table table-nowrap">
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">{{__('messages.note')}}</th>
                    <th scope="col">{{__('messages.user')}}</th>
                </thead>
                <tbody>
                @foreach($consultation->notes as $note)
                    <tr id="role{{$note->id}}Row">
                        <th scope="row">
                            <a href="#" class="fw-semibold">#{{$loop->iteration}}</a>
                        </th>
                        <td>{{$note->text}}</td>
                        <td>{{$note->user->name}}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
