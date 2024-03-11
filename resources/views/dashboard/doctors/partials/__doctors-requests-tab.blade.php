<x-filter/>
<div class="row">
    <div class="col-md-8">
        <table class="table table-nowrap">
            <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">{{__('messages.name')}}</th>
                <th scope="col">{{__('messages.speciality')}}</th>
                <th scope="col">{{__('messages.medical_id')}}</th>
                <th scope="col">{{__('messages.national_id')}}</th>
                <th scope="col">{{__('messages.phone')}}</th>
                <th scope="col">{{__('messages.actions')}}</th>
            </tr>
            </thead>
            <tbody>
            @foreach($resources as $resource)
                <tr id="role{{$resource->id}}Row">
                    <th scope="row">
                        <a href="#" class="fw-semibold">#{{$loop->iteration}}</a>
                    </th>
                    <td>{{$resource->user->name}}</td>
                    <td>{{$resource->user->name}}</td>
                    <td>{{$resource->medical_id}}</td>
                    <td>{{$resource->national_id}}</td>
                    <td>{{$resource->user->phone}}</td>
                    <td>
                        @include('dashboard.partials.__table-actions', ['resource' => $resource, 'route' => 'doctors'])
                        <a class="link-success approve-doctor cursor-pointer" data-id="{{$resource->id}}">
                            {{__('messages.approve')}} <i class="bi bi-check"></i>
                        </a>
                        <form action="{{route("doctors.approve", $resource->id)}}" class="d-inline" method="POST" id="approveResourceForm-{{$resource->id}}">
                            @csrf
                            @method('PUT')
                        </form>
                        <a class="link-warning reject-doctor cursor-pointer" data-id="{{$resource->id}}">
                            {{__('messages.reject')}} <i class="bi bi-sign-stop"></i>
                        </a>
                        <form action="{{route("doctors.reject", $resource->id)}}" class="d-inline" method="POST" id="rejectResourceForm-{{$resource->id}}">
                            @csrf
                            @method('PUT')
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        {{$resources->onEachSide(2)->withQueryString()->links()}}
    </div>
    <div class="col-md-4"></div>
</div>
@push('scripts')
    <script>
        $(document).ready(function() {
            $('.approve-doctor').on('click', function(e) {
                e.preventDefault();
                let id = $(this).data('id');
                Swal.fire({
                    title: 'Are you sure?',
                    text: "{{__('messages.confirm.doctor_approve')}}",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#2a4fd7',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, change it!'
                }).then((result) => {
                    if (result.isConfirmed && result.value) {
                        $('#approveResourceForm-' + id).submit();
                    }
                })
            })

            $('.reject-doctor').on('click', function(e) {
                e.preventDefault();
                let id = $(this).data('id');
                Swal.fire({
                    title: 'Are you sure?',
                    text: "{{__('messages.confirm.doctor_reject')}}",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#2a4fd7',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, change it!'
                }).then((result) => {
                    if (result.isConfirmed && result.value) {
                        $('#rejectResourceForm-' + id).submit();
                    }
                })
            })
        });
    </script>
    @endPush