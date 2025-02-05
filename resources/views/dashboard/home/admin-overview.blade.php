@extends('dashboard.layouts.master')
@section('title')
    {{__('messages.overview')}}
@endsection
@section('content')
    <x-breadcrumb title="{{__('messages.overview')}}" pagetitle="{{__('messages.tabibak')}}" route="{{route('overview')}}"/>
       <div class="d-flex align-items-center justify-content-end gap-5">
             <button type="button" onclick="downloadPDF()" class="my-5" style="background-color: transparent;border:none ;font-size:20px;text-decoration: underline;padding:0 5px;font-weight: 400 ">
                <svg style="margin: 0 5px" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M21 15V19C21 19.5304 20.7893 20.0391 20.4142 20.4142C20.0391 20.7893 19.5304 21 19 21H5C4.46957 21 3.96086 20.7893 3.58579 20.4142C3.21071 20.0391 3 19.5304 3 19V15M7 10L12 15M12 15L17 10M12 15V3" stroke="#1E1E1E" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                {{__('messages.download_PDF')}}  
            </button>
        </div>
    <div id="myBillingArea">
    <div class="col-xxl-12 col-lg-6 order-first" >
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
    <hr/>
    <h4 class="pt-5 pb-3">{{__('messages.session_insight')}}</h4>
        <div class="col-xxl-12 col-lg-6 order-first" >
            <div class="row row-cols-xxl-4 row-cols-1">
                 <x-overview-card title="{{__('messages.total_appointments')}}" icon="bi bi-clock" color="warning" count="{{$totalAppointments}}" />
                 <x-overview-card title="{{__('messages.total_pending_bookings')}}" icon="bi bi-stop-circle" color="info" count="{{$totalPendingBookings}}" />
                 <x-overview-card title="{{__('messages.total_completed_bookings')}}" icon="bi bi-calendar-plus" color="success" count="{{$totalCompletedBookings}}" />
                 <x-overview-card title="{{__('messages.total_canceled_bookings')}}" icon="bi bi-person-x" color="dark" count="{{$totalCanceledBookings}}" />
                 <x-overview-card title="{{__('messages.total_rescheduled')}}" icon="bi bi-card-list" color="primary" count="{{$totalRescheduled}}" />
                 <x-overview-card title="{{__('messages.Average_duration_of_consultation')}}" icon="bi bi-clock-history" color="secondary" count="{{$AverageDurationOfConsultation}}" />
                 <x-overview-card title="{{__('messages.total_video_consultations_completed')}}" icon="bi bi-camera-video" color="danger" count="{{$totalVideoConsultationsCompleted}}" />
                 <x-overview-card title="{{__('messages.total_audio_consultation_complete')}}" icon="bi bi-soundwave" color="warning" count="{{$totalAudioConsultationComplete}}" />
                 <x-overview-card title="{{__('messages.total_chat_consultation_complete')}}" icon="bi bi-chat-left-quote" color="secondary" count="{{$totalChatConsultationComplete}}" />
                 <x-overview-card title="{{__('messages.total_new_patients')}}" icon="bi bi-person-plus" color="success" count="{{$totalNewPatients}}" />
             </div>
        </div>
        <div class="row  ">
            <div class="col-xl-6">
                <div class="card  ">
                    <div class="card-header  ">
                        <h4 class="card-title mb-0 text-center">{{__('messages.top_lonsultation_locations')}}</h4>
                    </div>  
                    <div class="card-body">
                                    <div id="simple_pie_chart" data-colors='["--tb-primary", "--tb-success", "--tb-warning", "--tb-danger", "--tb-info"]' class="apex-charts" dir="ltr"></div>
                    </div> 
                </div> 
            </div>
            <div class="col-xl-6 mt-5">
                <div class="card  ">
                    <div class="card-header  ">
                        <h4 class="card-title mb-0 text-center">{{__('messages.speciality_insights')}}</h4>
                    </div> 
                    <div class="card-body">
                        <div id="bar_chart" data-colors='["--tb-primary"]' class="apex-charts" dir="ltr"></div>
                    </div> 
                </div> 
            </div>
        </div>
    <hr/>
    <h4 class="pt-5 pb-3">{{__('messages.session_insight')}}</h4>
        <div class="col-xxl-12 col-lg-6 order-first" >
            <div class="row row-cols-xxl-4 row-cols-1">
            <x-overview-card title="{{__('messages.doctors')}}" icon="bi bi-journal-plus" color="warning" count="{{$doctorsCount}}" />
            <x-overview-card title="{{__('messages.average_rating_per_doctor')}}" icon="bi bi-star-half" color="info" count="{{$averageRatingPerDoctor}}" />
            <x-overview-card title="{{__('messages.average_number_of_consultations_per_doctor')}}" icon="bi bi-journal-plus" color="success" count="{{ number_format($averageNumberOfConsultationsPerDoctor, 1)}}" />
            <x-overview-card title="{{__('messages.average_consultation_duration_per_doctor')}}" icon="bi bi-journal-plus" color="dark" count="{{$averageConsultationDurationPerDoctor}}" />
                   
             </div>
        </div>
        <div class="doc-table">
            <h4 class="pt-4 pb-2">{{__('messages.top_performing_doctors')}}</h4>
            <table class="table border table-striped">
            <thead>
                <tr>
                <th scope="col">{{__('messages.doctor_name')}}</th>
                <th scope="col">{{__('messages.rating')}}</th>
                <th scope="col">{{__('messages.speciaity')}}</th>
                <th scope="col">{{__('messages.no_of_sessions')}}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($topThreeDoctors as $doctor)
                    <tr>
                        <td>{{ $doctor->user->name }}</td>
                        <td>{{ number_format($doctor->rates_avg_value, 1) }}</td>
                        <td>{{ $doctor->medicalSpecialities->first()?->name }}</td>
                        <td>{{ $doctor->consultations_count }}</td>
                    </tr>
                @endforeach
            </tbody>
            </table>
        </div>
         <div class="card  ">
            <div class="card-header  ">
                <h4 class="card-title mb-0 text-center">{{__('messages.most_booked_doctors')}}</h4>
            </div>  
        <div class="card-body">
            <div id="doctor_bar_chart" data-colors='["--tb-primary"]' class="apex-charts" dir="ltr"></div>
        </div> 
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
    <script src="{{ URL::asset('assets/js/pages/apexcharts-pie.init.js') }}"></script>
    <script src="{{ URL::asset('assets/libs/apexcharts/apexcharts.min.js') }}"></script>
    {{-- jspdf --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
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

    <script>
 
        var chartPieBasicColors = getChartColorsArray("simple_pie_chart");
            if(chartPieBasicColors){
            var options = {
                series: [44, 55, 13, 43, 22],
                chart: {
                    height: 300,
                    type: 'pie',
                },
                labels: ['Team A', 'Team B', 'Team C', 'Team D', 'Team E'],
                legend: {
                    position: 'bottom'
                },
                dataLabels: {
                    dropShadow: {
                        enabled: false,
                    }
                },
                colors: chartPieBasicColors
            };

            var chart = new ApexCharts(document.querySelector("#simple_pie_chart"), options);
            chart.render();
            }

        var chartBarColors = getChartColorsArray("bar_chart");
            if(chartBarColors){
            var options = {
                chart: {
                    height: 350,
                    type: 'bar',
                    toolbar: {
                        show: false,
                    }
                },
                plotOptions: {
                    bar: {
                        horizontal: true,
                    }
                },
                dataLabels: {
                    enabled: false
                },
                series: [{
                    data: [380, 430, 450, 475, 550, 584, 780, 1100, 1220, 1365]
                }],
                colors: chartBarColors,
                grid: {
                    borderColor: '#f1f1f1',
                },
                xaxis: {
                    categories: ['South Korea', 'Canada', 'United Kingdom', 'Netherlands', 'Italy', 'France', 'Japan', 'United States', 'China', 'Germany'],
                    // title: {
                    //     text: 'Speciality Insights',
                    //     align: 'left',
                    //     margin: 10,
                    //     offsetX: 0,
                    //     offsetY: 0,
                    //     floating: false,
                    //     style: {
                    //       fontSize:  '14px',
                    //       fontWeight:  'bold',
                    //       fontFamily:  undefined,
                    //       color:  '#000'
                    //     },
                    // }
                },
  

            }
            var chart = new ApexCharts(document.querySelector("#bar_chart"),options);
            chart.render();
            }
        var chartBarColors = getChartColorsArray("doctor_bar_chart");
            if(chartBarColors){
            var options = {
                chart: {
                    height: 350,
                    type: 'bar',
                    toolbar: {
                        show: false,
                    }
                },
                plotOptions: {
                    bar: {
                        horizontal: true,
                    }
                },
                dataLabels: {
                    enabled: false
                },
                series: [{
                    data: [380, 430, 450, 475, 550, 584, 780, 1100, 1220, 1365]
                }],
                colors: chartBarColors,
                grid: {
                    borderColor: '#f1f1f1',
                },
                xaxis: {
                    categories: ['South Korea', 'Canada', 'United Kingdom', 'Netherlands', 'Italy', 'France', 'Japan', 'United States', 'China', 'Germany'],
                    // title: {
                    //     text: 'Speciality Insights',
                    //     align: 'left',
                    //     margin: 10,
                    //     offsetX: 0,
                    //     offsetY: 0,
                    //     floating: false,
                    //     style: {
                    //       fontSize:  '14px',
                    //       fontWeight:  'bold',
                    //       fontFamily:  undefined,
                    //       color:  '#000'
                    //     },
                    // }
                },
  

            }
            var chart = new ApexCharts(document.querySelector("#doctor_bar_chart"),options);
            chart.render();
            }
  
    </script>
    <script>
 
window.jsPDF = window.jspdf.jsPDF;

 
function downloadPDF() {
    var elementHTML = document.querySelector("#myBillingArea");

    html2canvas(elementHTML, { 
        scale: 2,  
        logging: true,  
        useCORS: true,  
    }).then(function(canvas) {
        var docPDF = new jsPDF('p', 'mm', 'a4'); 
        
         var pageWidth = docPDF.internal.pageSize.getWidth();
        var pageHeight = docPDF.internal.pageSize.getHeight();
        
        var imgData = canvas.toDataURL('image/png');

         var imageWidth = pageWidth - 20;  
        var imageHeight = canvas.height * imageWidth / canvas.width;

         if (imageHeight > pageHeight - 20) {
            imageHeight = pageHeight - 20;
            imageWidth = canvas.width * imageHeight / canvas.height;
        }

         docPDF.addImage(imgData, 'PNG', 10, 10, imageWidth, imageHeight);
        
         docPDF.save('Tabibak_report.pdf');
    });
}
    </script>
@endpush


