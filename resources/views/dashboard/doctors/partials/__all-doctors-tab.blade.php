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
            </tr>
            </thead>
            <tbody>
            @foreach($resources as $resource)
                <tr id="role{{$resource->id}}Row">
                    <th scope="row">
                        <a href="{{route('doctors.show', $resource->id)}}" class="fw-semibold">#{{$loop->iteration}}</a>
                        <!-- <a href="#" class="fw-semibold">#{{$loop->iteration}}</a> -->
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
                    @include('dashboard.partials.__table-actions', ['resource' => $resource, 'route' => 'doctors', 'showModel' => false])
                </tr>
            @endforeach
            </tbody>
        </table>
        @include('dashboard.layouts.paginate')
    </div>
</div>
