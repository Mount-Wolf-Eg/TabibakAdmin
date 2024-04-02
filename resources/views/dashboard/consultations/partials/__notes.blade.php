<div class="card">
    <div class="card-header">
        <h5 class="d-inline">
            <i class="bi bi-text-paragraph mx-2"></i>
            {{__('messages.notes')}}
        </h5>
        <button type="button" class="btn btn-primary btn-sm float-{{app()->getLocale() == 'ar' ? 'start' : 'end'}}" data-bs-toggle="modal"
                data-bs-target="#noteForm">
            <i class="bi bi-plus"></i>
            {{__('messages.add_note')}}
        </button>
        @include('dashboard.consultations.partials.__note-form')
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-12">
                <table class="table table-nowrap">
                    <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">{{__('messages.note')}}</th>
                        <th scope="col">{{__('messages.user')}}</th>
                        <th scope="col">{{__('messages.date')}}</th>
                    </thead>
                    <tbody>
                    @foreach($consultation->notes as $note)
                        <tr id="role{{$note->id}}Row">
                            <th scope="row">
                                <a href="#" class="fw-semibold">#{{$loop->iteration}}</a>
                            </th>
                            <td>{{$note->text}}</td>
                            <td>{{$note->user->name}}</td>
                            <td>{{$note->created_at->format('Y-m-d h:i A')}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


