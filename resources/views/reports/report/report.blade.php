<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medical Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
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
            background-image: url('{{ public_path("assets/reports/report/prescription Bckground en.png") }}');
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
            margin-top: 50px;
        }

        .doctor-info h1 {
            font-weight: bold;
        }

        .doctor-info p {
            font-size: 12px;
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
        }
    </style>
</head>

<body>
    <section class="section">
        <div class="card">
            <div class="header">
                <div class="doctor-info">
                    <h1>Dr. {{ $consultation->doctor?->user?->name }}</h1>
                    <p>{{ $consultation->medicalSpeciality?->name }}</p>
                    <p>Date: {{ $consultation->created_at->format('d/m/Y') }} {{ $consultation->created_at->format('h:i A') }}</p>
                </div>
            </div>

            <div class="patient-info">
                <p>Patient Name: {{ $consultation->patient?->user?->name }}</p>
            </div>

            <div class="report">
                <h3 style="font-size: 15px;">Report</h3>
                <p>
                    {{ $report }}
                </p>
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
                <p style="font-size: 14px;">This notice is an official document and does not require a signature or
                    stamp.</p>
                <p style="font-size: 14px;">vtmc</p>
            </div>
        </div>

        </div>
    </section>
</body>

</html>