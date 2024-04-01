<div class="card">
    <div class="card-header">
        <h5>
            <i class="bi bi-file-medical-fill mx-2"></i>
            {{__('messages.medicines')}}
        </h5>
    </div>
    <div class="card-body">
        <table class="table table-bordered">
            <thead>
            <tr>
                <th>{{__('messages.name')}}</th>
                <th>{{__('messages.quantity')}}</th>
                <th>{{__('messages.strength')}}</th>
                <th>{{__('messages.dosage')}}</th>
            </tr>
            </thead>
            <tbody>
            @if($consultation->prescription->isEmpty())
                <tr>
                    <td colspan="4" class="text-center">{{__('messages.no_medicines')}}</td>
                </tr>
            @else
                @foreach($consultation->prescription as $medicine)
                    <tr>
                        <td>{{$medicine['name']}}</td>
                        <td>{{$medicine['quantity']}}</td>
                        <td>{{$medicine['strength']}}</td>
                        <td>{{$medicine['dosage']}}</td>
                    </tr>
                @endforeach
            @endif
            </tbody>
        </table>
    </div>
</div>
