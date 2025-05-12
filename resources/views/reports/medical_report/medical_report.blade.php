<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medical Report</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;

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
            background-image: url('{{ public_path("assets/reports/prescription/prescription Bckground en.png") }}');
            margin: 20px auto;
            padding: 20px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            grid-template-rows: auto auto auto;
            background-color: #fff;
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
            margin-top: 50px;
        }

        .doctor-info h1 {
            font-weight: bold;
        }

        .doctor-info p {
            font-size: 14px;
            color: #000000;
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
            font-size: 18px;
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
            /* grid-column: 1 / 3; */

        }

        .prescription-header img {
            width: 30px;
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
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
            font-weight: 100;
        }

        th {
            background: #f9f9f9;
            font-weight: bold;
        }


        .table-header {
            grid-column: 1 / 3;
        }

        .table-header h3 {
            text-align: center;
        }

        .table-header p {
            text-align: left;
        }

        @media (max-width:992px) {
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

            .prescription-header img {
                width: 71px;
                margin: 40px 0 0 0;
            }

            .prescription-header {
                grid-column: 1 / 7;
                grid-row: 1;
            }

            .table-header {
                grid-column: 1 / 7;
            }

            .table-header h3 {
                margin-bottom: 20px;
            }
        }
    </style>
</head>

<body>
    <section class="section">
        <div class="card">
            <div class="prescription-header">
                <img style="width: 100px;" src="{{ public_path('assets/reports/medical_report/logo.png') }}" alt="Prescription Icon">
            </div>

            <div class="header">
                <div class="doctor-info">
                    <p>Date : {{ $consultation->created_at->format('d/m/Y') }} {{ $consultation->created_at->format('h:i A') }}</h1>
                    <p>Licensing : </p>
                    <p>Commercial registration number : 1009062980</p>
                </div>
            </div>
            <!-- <div class="patient-info">
                <p>Patient name</p>
                <p><strong>{{ $consultation->patient?->user?->name }}</strong></p>
            </div> -->

            <div class="table-header">
                <h3> Prescription Report </h3>
                <p>Patient Name: {{ $consultation->patient?->user?->name }}</p>
                <p>
                    Doctor: {{ $consultation->doctor?->user?->name }}
                </p>
                <table>
                <thead>
                        <tr>
                            <th>Medicine Name</th>
                            <th>Time</th>
                            <th>Strength</th>
                            <th>QTY</th>
                            <th>Dose</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($medications as $medicine)
                        <tr>
                            <td>{{ $medicine['name'] }}</td>
                            <td>{{ trans('messages.' . $medicine['time']) }}</td>
                            <td>{{ $medicine['strength'] }}</td>
                            <td>{{ $medicine['quantity'] }}</td>
                            <td>{{ $medicine['dosage'] }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- <div class="signature">
                <img src="Screenshot 2025-03-12 021240.png" alt="Signature" width="150">
            </div> -->

            <div class="qr-code">
                <img src="data:image/png;base64,{{ $qrCode }}" alt="Prescription QR Code" width="70">
                <p style="font-size: 7px;">Prescription code</p>
            </div>

            <div class="footer">
                <hr>
                <p style="font-size: 14px;">This notice is an official document and does not require a signature or stamp.</p>
                <p style="font-size: 14px;">vtmc</p>
            </div>
        </div>

        </div>
    </section>
</body>

</html>