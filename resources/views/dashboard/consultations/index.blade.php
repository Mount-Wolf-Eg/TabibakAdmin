@extends('dashboard.layouts.master')
@section('title')
    {{__('messages.manage_referrals')}}
@endsection
@section('content')
    <x-breadcrumb title="{{__('messages.manage_referrals')}}"
                  pagetitle="{{__('messages.referrals')}}"
                  route="{{route('consultations.index')}}"/>
    <x-filter>
        <div class="col-lg-2">
            {{ Form::label('Date', __('messages.date'), ['class' => 'form-label']) }}
            {!! Form::date('creationDate' , request('creationDate'), ['class' => 'form-control']) !!}
        </div>
        <div class="col-lg-2">
            {{ Form::label('doctor', __('messages.doctor'), ['class' => 'form-label']) }}
            {!! Form::select('doctor' , $doctors->pluck('user.name', 'id')->prepend(__('messages.select'), ''),
                request('doctor'),  ['class' => 'form-control select2']) !!}
        </div>
        <div class="col-lg-2">
            {{ Form::label('urgency_levels', __('messages.urgency_levels'), ['class' => 'form-label']) }}
            {!! Form::select('type' , $types->pluck('txt', 'value')->prepend(__('messages.select'), ''),
                request('type'),  ['class' => 'form-control select2']) !!}
        </div>
        @if(auth()->user()->vendor)
            <div class="col-lg-2">
                {{ Form::label('status', __('messages.status'), ['class' => 'form-label']) }}
                {!! Form::select('myVendorStatus' , $vendorStatuses->pluck('txt', 'value')->prepend(__('messages.select'), ''),
                    request('myVendorStatus'),  ['class' => 'form-control select2']) !!}
            </div>
        @endif
    </x-filter>
    <div class="row">
        <div class="col-12">
            <table class="table table-nowrap">
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">{{__('messages.request_id')}}</th>
                    <th scope="col">{{__('messages.reporting_date')}}</th>
                    <th scope="col">{{__('messages.patient_name')}}</th>
                    <th scope="col">{{__('messages.doctor_name')}}</th>
                    <th scope="col">{{__('messages.doctor_phone')}}</th>
                    <th scope="col">{{__('messages.urgency_level')}}</th>
                    <th scope="col">{{__('messages.referral_reason')}}</th>
                    <th scope="col">{{__('messages.actions')}}</th>
                    @if(auth()->user()?->vendor)
                        <th scope="col">{{__('messages.vendor_status')}}</th>
                        <th scope="col">{{__('messages.request_actions')}}</th>
                    @endif
                </tr>
                </thead>
                <tbody>
                @foreach($resources as $resource)
                    <tr id="role{{$resource->id}}Row">
                        <th scope="row">
                            <a href="#" class="fw-semibold">#{{$loop->iteration}}</a>
                        </th>
                        <td><a href="{{route('consultations.show', $resource->id)}}">#{{$resource->id}}</a></td>
                        <td>{{$resource->created_at->format('Y-m-d h:i A')}}</td>
                        <td>{{$resource->patient->user->name}}</td>
                        <td>{{$resource->doctor?->user->name}}</td>
                        <td>{{$resource->doctor?->user->phone}}</td>
                        <td>{{$resource->type?->label()}}</td>
                        <td>{{$resource->transfer_reason}}</td>
                        @include('dashboard.partials.__table-actions', ['resource' => $resource, 'disableEdit' => true,
                        'disableDelete' => !auth()->user()->can('delete-consultation'),
                        'route' => 'consultations', 'hideActive' => true, 'showModel' => false])
                        @if(auth()->user()?->vendor)
                            <td><span
                                    class="text-{{$resource->getVendorStatusColor(auth()->user()->vendor->id)}}">{{$resource->getVendorStatusTxt(auth()->user()->vendor->id)}}
                            </td>
                            <td>
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
                                @endif
                            </td>
                        @endif
                    </tr>
                @endforeach
                </tbody>
            </table>
            @include('dashboard.layouts.paginate')
        </div>
    </div>
@endsection
@push('scripts')
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
@endPush
