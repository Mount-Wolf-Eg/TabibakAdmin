<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Medical Report</title>
    <style>
        body {
            /* font-family: Arial, sans-serif; */
            font-family: "DejaVu Sans", Arial, sans-serif;
            font-size: 16px;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
        }

        h1 {
            font-size: 20px;
            font-weight: bold;
            margin: 0;
        }

        p {
            margin: 5px;
        }

        .section {
            width: 100%;
            padding: 40px;
            display: grid;
            gap: 20px;
            border-radius: 8px;
        }

        .card {
            min-height: 100vh;
            width: 100%;
            max-width: 800px;
            /* Set max width for better PDF layout */
            background-image: url('{{ public_path("assets/reports/prescription/prescription Bckground en.png") }}');
            margin: 20px auto;
            padding: 20px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            grid-template-rows: auto auto auto;
            gap: 20px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            overflow: hidden;
            background-size: cover;
            background-position: center;
            display: grid;
            grid-template-columns: 1fr 1fr;
            grid-template-rows: auto auto auto;
        }

        .header {
            grid-column: 1 / 2;
            grid-row: 1;
            margin-top: 20px;
        }

        .doctor-info h1 {
            font-weight: bold;
        }

        .doctor-info p {
            font-size: 12px;
            color: #000000;
            padding: 5px 0;
        }

        .hotline {
            margin-top: 10px;
            font-size: 14px;
            color: #777;
        }

        .patient-info {
            grid-column: 1 / 3;
            grid-row: 2;
            text-align: left;
            font-size: 13px;
        }

        .report {
            grid-column: 1 / 3;
            grid-row: 3;
            text-align: center;
            margin-top: 20px;
        }

        .report p {
            font-size: 12px;
            text-align: left;
            line-height: 1.5;
        }

        .signature {
            grid-column: 2;
            grid-row: 4;
            text-align: right;
            margin-top: 40px;
        }

        .qr-code {
            grid-column: 1;
            grid-row: 4;
            margin-top: 20px;
        }

        .footer {
            grid-column: 1 / 3;
            grid-row: 5;
            text-align: center;
            font-size: 14px;
            color: #777;
        }

        .prescription-header {
            text-align: right;
            grid-column: 1 / 3;
        }

        .prescription-header img {
            width: 50px;
            margin: auto;
        }

        .prescription-header h2 {
            margin: 0;
            font-size: 18px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            grid-column: 1 / 3;
            grid-row: 2;
        }

        th,
        td {
            font-size: 14px;
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }

        th {
            font-size: 12px;
            background: #f9f9f9;
            font-weight: bold;
        }

        td {
            font-size: 11px;
        }

        @media (max-width: 992px) {
            .card {
                width: 100%;
                margin: unset;
            }

            .section {
                max-width: 95%;
                padding: 40px;
                display: grid;
                gap: 0px;
                border-radius: 8px;
                padding: 0;
            }

            .report p {
                text-align: left;
                font-size: 12px;
                font-weight: 100;
                color: #777;
                line-height: 1.5;
            }

            .report {
                grid-column: 1 / 7;
            }

            .header {
                grid-column: 1 / 3;
                grid-row: 1;
            }

            .footer {
                grid-column: 1 / 7;
            }

            .prescription-header {
                grid-column: 1 / 7;
            }
        }
    </style>
</head>

<body>
    <section class="section">
        <div class="card">
            <div class="prescription-header">
                <img style="width: 80px; padding: 0 120 0 0" src="{{ public_path('assets/reports/medical_report/logo.png') }}" alt="Prescription Icon">
            </div>

            <div style="width: 100%; font-size: 14px;">
                <p>Clinic: Name</p>
                <p>Tax registration number: 1009062980</p>
                <p>License number: 4190</p>
            </div>

            <table style="width: 100%; font-size: 14px; border: none;">
                <tr>
                    <!-- Doctor Info -->
                    <td style="width: 50%; vertical-align: top; border: none">
                        <p>Doctor Name: Dr. {{ $consultation->doctor?->user?->name }}</p>
                        <p>Specialty: {{ $consultation->medicalSpeciality?->name }}</p>
                        <p>Date: {{ $consultation->created_at->format('d/m/Y') }} {{ $consultation->created_at->format('h:i A') }}</p>
                    </td>

                    <!-- Patient Info -->
                    <td style="width: 50%; vertical-align: top; border: none">
                        <p>Patient Name: {{ $consultation->patient?->user?->name }}</p>
                        <p>Patient age: {{ $consultation->patient?->user?->age }}</p>
                        <p>Patient gender: {{ $consultation->patient?->user?->gender->label() }}</p>
                    </td>
                </tr>
            </table>

            <div class="prescription-header" style="padding-top: 15px">
                <!-- <img src="{{ public_path('assets/reports/prescription/cropped-cropped-transparentlogo3000x750px.jpg') }}" alt="Prescription Icon" width="30"> -->
                <p style="font-size: 15px; text-align: center">Prescription Details</p>
                <table>
                    <thead>
                        <tr>
                            <th>Medicine Name</th>
                            <th>Time</th>
                            <th>Strength</th>
                            {{-- <th>QTY</th> --}}
                            <th>Dose</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($medications as $medicine)
                        <tr>
                            <td>{{ $medicine['name'] }}</td>
                            <td>{{ trans('messages.' . $medicine['time']) }}</td>
                            <td>{{ $medicine['strength'] }}</td>
                            {{-- <td>{{ $medicine['quantity'] }}</td> --}}
                            <td>{{ $medicine['dosage'] }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- @if($consultation->doctor_signature)
            <div class="signature" style="text-align: right; margin-top: 20px;">
                <img src="{{ storage_path('app/public/signatures/'.$consultation->doctor_signature) }}" alt="Doctor Signature" width="150">
            </div>
            @endif -->

            <div class="footer">
                <hr />
                <p style="font-size: 14px">
                    This notice is an official document and does not require a signature
                    or stamp.
                </p>
                <p style="font-size: 14px">vtmc</p>
            </div>
        </div>
    </section>
</body>

</html>