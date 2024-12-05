@extends('dashboard.layouts.master')
@section('title')
    {{__('messages.overview')}}
@endsection
@section('content')
    <x-breadcrumb title="{{__('messages.overview')}}" pagetitle="{{__('messages.Tell Doc')}}" route="{{route('overview')}}"/>
    <div class="col-xxl-12 col-lg-6 order-first">
        <div class="row row-cols-xxl-4 row-cols-1">
            <x-overview-card title="{{__('messages.patients')}}" icon="bi bi-person-badge" color="warning" count="{{$patientsCount}}" />
            <x-overview-card title="{{__('messages.doctors')}}" icon="bi bi-journal-plus" color="info" count="{{$doctorsCount}}" />
            <x-overview-card title="{{__('messages.vendors')}}" icon="bi bi-houses" color="success" count="{{$vendorsCount}}" />
            <x-overview-card title="{{__('messages.hospitals')}}" icon="bi bi-hospital" color="dark" count="{{$hospitalsCount}}" />
            <x-overview-card title="{{__('messages.clinics')}}" icon="bi bi-clipboard-pulse" color="primary" count="{{$clinicsCount}}" />
            <x-overview-card title="{{__('messages.pharmacies')}}" icon="bi bi-prescription2" color="secondary" count="{{$pharmaciesCount}}" />
            <x-overview-card title="{{__('messages.Home_cares')}}" icon="bi bi-chat-heart" color="danger" count="{{$homeCaresCount}}" />
            <x-overview-card title="{{__('messages.labs')}}" icon="bi bi-stack" color="warning" count="{{$labsCount}}" />
            <x-overview-card title="{{__('messages.total_transactions')}}" icon="bi bi-currency-exchange" color="secondary" count="{{$totalTransactions}}" />
            <x-overview-card title="{{__('messages.total_revenues')}}" icon="bi bi-wallet" color="success" count="{{$totalRevenues}}" />
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


