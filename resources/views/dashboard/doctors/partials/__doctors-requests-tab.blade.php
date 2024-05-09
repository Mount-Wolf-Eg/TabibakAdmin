<x-filter/>
<div class="row">
    <div class="col-12">
        <table class="table table-nowrap">
            <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">{{__('messages.name')}}</th>
                <th scope="col">{{__('messages.speciality')}}</th>
                <th scope="col">{{__('messages.medical_id')}}</th>
                <th scope="col">{{__('messages.national_id')}}</th>
                <th scope="col">{{__('messages.phone')}}</th>
                <th scope="col">{{__('messages.activation')}}</th>
                <th scope="col">{{__('messages.actions')}}</th>
                <th scope="col">{{__('messages.request_actions')}}</th>
            </tr>
            </thead>
            <tbody>
            @foreach($resources as $resource)
                <tr id="role{{$resource->id}}Row">
                    <th scope="row">
                        <a href="#" class="fw-semibold">#{{$loop->iteration}}</a>
                    </th>
                    <td>{{$resource->user?->name}}</td>
                    <td>
                        @foreach($resource->medicalSpecialities as $speciality)
                            - {{$speciality->name}} <br>
                        @endforeach
                    </td>
                    <td>{{$resource->medical_id}}</td>
                    <td>{{$resource->national_id}}</td>
                    <td>{{$resource->user?->phone}}</td>
                    @include('dashboard.partials.__table-actions', ['resource' => $resource, 'route' => 'doctors', 'showModel' => true])
                    @include('dashboard.doctors.requests-show', ['resource' => $resource])
                    <td>
                        <a class="link-success approve-doctor cursor-pointer px-2" data-id="{{$resource->id}}">
                            {{__('messages.approve')}} <i class="bi bi-check"></i>
                        </a>
                        <form action="{{route("doctors.approve", $resource->id)}}" class="d-inline" method="POST" id="approveResourceForm-{{$resource->id}}">
                            @csrf
                            @method('PUT')
                        </form>
                        <a class="link-warning reject-doctor cursor-pointer px-2" data-id="{{$resource->id}}">
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
        @include('dashboard.layouts.paginate')
    </div>
</div>
@push('scripts')
    <script>
        $(document).ready(function() {
            $('.approve-doctor').on('click', function(e) {
                e.preventDefault();
                let id = $(this).data('id');
                Swal.fire({
                    title: '{{__('messages.confirm.are_you_sure')}}',
                    text: "{{__('messages.confirm.doctor_approve')}}",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#2a4fd7',
                    cancelButtonColor: '#d33',
                    confirmButtonText: '{{__('messages.confirm.yes_change')}}',
                    cancelButtonText: '{{__('messages.confirm.cancel')}}',
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
                    title: '{{__('messages.confirm.are_you_sure')}}',
                    text: "{{__('messages.confirm.doctor_reject')}}",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#2a4fd7',
                    cancelButtonColor: '#d33',
                    confirmButtonText: '{{__('messages.confirm.yes_change')}}',
                    cancelButtonText: '{{__('messages.confirm.cancel')}}',
                }).then((result) => {
                    if (result.isConfirmed && result.value) {
                        $('#rejectResourceForm-' + id).submit();
                    }
                })
            })
        });
    </script>
    @endPush
