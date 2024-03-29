@extends('dashboard.layouts.master')
@section('title')
    {{__('messages.overview')}}
@endsection
@section('content')
    <x-breadcrumb title="{{__('messages.overview')}}" pagetitle="{{__('messages.tabibak')}}" route="{{route('overview')}}"/>
    <div class="col-xxl-12 col-lg-6 order-first">
        <div class="row row-cols-xxl-4 row-cols-1">
            @php($components = [
                'patients' => [
                    'title' => __('messages.patients'),
                    'count' => $patientsCount,
                    'icon' => 'bi bi-person-badge',
                    'color' => 'warning'
                ],
                'doctors' => [
                    'title' => __('messages.doctors'),
                    'count' => $doctorsCount,
                    'icon' => 'bi bi-journal-plus',
                    'color' => 'info'
                ],
                'vendors' => [
                    'title' => __('messages.vendors'),
                    'count' => $vendorsCount,
                    'icon' => 'bi bi-houses',
                    'color' => 'success'
                ],
                'hospitals' => [
                    'title' => __('messages.hospitals'),
                    'count' => $hospitalsCount,
                    'icon' => 'bi bi-hospital',
                    'color' => 'dark'
                ],
                'clinics' => [
                    'title' => __('messages.clinics'),
                    'count' => $clinicsCount,
                    'icon' => 'bi bi-clipboard-pulse',
                    'color' => 'primary'
                ],
                'pharmacies' => [
                    'title' => __('messages.pharmacies'),
                    'count' => $pharmaciesCount,
                    'icon' => 'bi bi-prescription2',
                    'color' => 'secondary'
                ],
                'home_cares' => [
                    'title' => __('messages.Home_cares'),
                    'count' => $homeCaresCount,
                    'icon' => 'bi bi-chat-heart',
                    'color' => 'danger'
                ],
                'labs' => [
                    'title' => __('messages.labs'),
                    'count' => $labsCount,
                    'icon' => 'bi bi-stack',
                    'color' => 'warning'
                ],
                'transactions' => [
                    'title' => __('messages.total_transactions'),
                    'count' => $totalTransactions,
                    'icon' => 'bi bi-currency-exchange',
                    'color' => 'secondary'
                ],
                'revenues' => [
                    'title' => __('messages.total_revenues'),
                    'count' => $totalRevenues,
                    'icon' => 'bi bi-wallet',
                    'color' => 'success'
                ],
            ])
            @foreach($components as $ele)
                <!-- card -->
                <div class="col my-2">
                    <div class="card card-animate">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div class="vr rounded bg-{{$ele['color']}} opacity-50" style="width: 4px;"></div>
                                <div class="flex-grow-1 {{ app()->getLocale() == 'ar' ? 'me-3' : 'ms-3' }}">
                                    <p class="text-uppercase fw-medium text-{{$ele['color']}} fs-14 text-truncate">{{$ele['title']}}</p>
                                    <h4 class="fs-22 fw-semibold mb-3">{{$ele['count']}}</h4>
                                </div>
                                <div class="avatar-sm flex-shrink-0">
                                <span class="avatar-title bg-secondary-subtle text-{{$ele['color']}} rounded fs-3">
                                    <i class="{{$ele['icon']}}"></i>
                                </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection

@section('scripts')
    <!-- prismjs plugin -->
    <script src="{{ URL::asset('assets/libs/prismjs/prism.js') }}"></script>
    <script src="{{ URL::asset('assets/js/app.js') }}"></script>
@endsection
@push('scripts')
    <script src="{{ URL::asset('assets/js/jquery-3.6.0.min.js') }}"></script>
    <!-- flatpickr.js -->
    <script type='text/javascript' src='{{ URL::asset('build/libs/flatpickr/flatpickr.min.js') }}'></script>
    <!-- ckeditor -->
    <script src="{{ URL::asset('assets/libs/@ckeditor/ckeditor5-build-classic/ckeditor.js') }}"></script>
    <script src="{{ URL::asset('assets/js/pages/form-editor.init.js') }}"></script>

    <!-- dropzone js
    <script src="{ { URL::asset('assets/libs/dropzone/dropzone-min.js') }}"></script> -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/dropzone.js" integrity="sha512-9e9rr82F9BPzG81+6UrwWLFj8ZLf59jnuIA/tIf8dEGoQVu7l5qvr02G/BiAabsFOYrIUTMslVN+iDYuszftVQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/dropzone.css" integrity="sha512-7uSoC3grlnRktCWoO4LjHMjotq8gf9XDFQerPuaph+cqR7JC9XKGdvN+UwZMC14aAaBDItdRj3DcSDs4kMWUgg==" crossorigin="anonymous" referrerpolicy="no-referrer" />


    <script>
        var Token = $("meta[name=csrf-token]").attr("content");
        var thumbnailArray = [];
        var myDropzone = new Dropzone("div.my-dropzone", {
            url: "",
            method: "POST",
            params:{
                filePath: "images/Posts",
                _token: Token
            },
            addRemoveLinks: true,
            uploadMultiple: false,
            maxFiles: 1,
            parallelUploads: 1,
            acceptedFiles: ".jpeg, .jpg, .png, .gif",
            maxFilesize: 2,
            timeout: 5000,
            removedfile: function (file) {
                file.previewElement.remove();
                thumbnailArray = [];
            },
            success: function(file, response)
            {
                $("#PostImage").val(response.fileName);
                //console.log(response);
            },
            error: function(file, response)
            {
                //alert(response);
                $("#WarningMessage").show();
                $("#WarningMessage .alert").text(response);
                $("#WarningMessage").delay(5000).hide("slow");
                file.previewElement.remove();
                thumbnailArray = [];
            }
        });

        myDropzone.on("thumbnail", function (file, dataUrl) {
            thumbnailArray.push(dataUrl);
        });
        var mockFile = { name: "Existing file!", size: 12345 };


    </script>


    <script>
        function addLocation(locationURL){
            console.log(locationURL);
            $("#DeleteThisRecord").prop("href", locationURL);
        }
    </script>
@endpush


