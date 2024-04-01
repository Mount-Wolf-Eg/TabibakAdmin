@if($resource->isPendingVendor(auth()->user()?->vendor->id))
    <a class="link-success accept-vendor-consultation cursor-pointer px-2"
       data-id="{{$resource->id}}">
        {{__('messages.accept')}} <i class="bi bi-check"></i>
    </a>
    <form action="{{route("consultations.vendor-accept", $resource->id)}}"
          class="d-inline"
          method="POST" id="acceptResourceForm-{{$resource->id}}">
        @csrf
        @method('PUT')
    </form>
    <a class="link-warning reject-vendor-consultation cursor-pointer px-2"
       data-id="{{$resource->id}}">
        {{__('messages.reject')}} <i class="bi bi-sign-stop"></i>
    </a>
    <form action="{{route("consultations.vendor-reject", $resource->id)}}"
          class="d-inline"
          method="POST" id="rejectResourceForm-{{$resource->id}}">
        @csrf
        @method('PUT')
    </form>
@else
    <span class="text-{{$resource->getVendorStatusColor(auth()->user()->vendor->id)}}">
        {{__('messages.already')}} {{$resource->getVendorStatusTxt(auth()->user()->vendor->id)}}
    </span>
@endif

@pushonce('scripts')
    <script>
        $(document).ready(function () {
            $('.accept-vendor-consultation').on('click', function (e) {
                e.preventDefault();
                let id = $(this).data('id');
                Swal.fire({
                    title: '{{__('messages.confirm.are_you_sure')}}',
                    text: "{{__('messages.confirm.vendor_approve_case')}}",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#2a4fd7',
                    cancelButtonColor: '#d33',
                    confirmButtonText: '{{__('messages.confirm.yes_change')}}',
                    cancelButtonText: '{{__('messages.confirm.cancel')}}',
                }).then((result) => {
                    if (result.isConfirmed && result.value) {
                        $('#acceptResourceForm-' + id).submit();
                    }
                })
            })

            $('.reject-vendor-consultation').on('click', function (e) {
                e.preventDefault();
                let id = $(this).data('id');
                Swal.fire({
                    title: '{{__('messages.confirm.are_you_sure')}}',
                    text: "{{__('messages.confirm.vendor_reject_case')}}",
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
@endpushonce
