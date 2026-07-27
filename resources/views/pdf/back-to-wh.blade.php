<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Back To Warehouse Note</title>

    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 10px;
            color: #333;
            line-height: 1.5;
            margin: 0;
            padding: 0;
        }

        .container {
            padding: 20px;
        }

        .header-table {
            width: 100%;
            border-bottom: 2px solid #0d9488;
            margin-bottom: 20px;
            padding-bottom: 10px;
        }

        .header-table td {
            vertical-align: middle;
        }

        .report-title {
            font-size: 22px;
            font-weight: bold;
            color: #0d9488;
            margin: 0;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .company-info {
            text-align: right;
            font-size: 12px;
            font-weight: bold;
            color: #7f8c8d;
        }

        .info-section {
            width: 100%;
            margin-bottom: 25px;
        }

        .info-column {
            width: 48%;
            vertical-align: top;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
        }

        .info-table td {
            padding: 4px 0;
            font-size: 11px;
        }

        .info-table td.label {
            width: 110px;
            color: #7f8c8d;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 9px;
        }

        .info-table td.value {
            color: #2c3e50;
            font-weight: 500;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            border-radius: 8px;
            overflow: hidden;
        }

        .data-table th {
            background-color: #0d9488;
            color: #ffffff;
            text-align: center;
            font-weight: bold;
            padding: 10px 8px;
            text-transform: uppercase;
            font-size: 10px;
            border: 1px solid #0d9488;
        }

        .data-table td {
            padding: 8px;
            border: 1px solid #e1e8ed;
            vertical-align: middle;
        }

        .data-table tr:nth-child(even) {
            background-color: #f8f9fa;
        }

        .text-center {
            text-align: center;
        }

        .badge {
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 9px;
            font-weight: bold;
            display: inline-block;
        }

        .badge-good {
            background-color: #d4edda;
            color: #155724;
        }

        .badge-scrape {
            background-color: #fff3cd;
            color: #856404;
        }

        .badge-damage {
            background-color: #f8d7da;
            color: #721c24;
        }

        .footer {
            margin-top: 30px;
            padding-top: 10px;
            border-top: 1px solid #e1e8ed;
            font-size: 9px;
            color: #95a5a6;
            text-align: center;
        }

        .page-number {
            position: fixed;
            bottom: 20px;
            right: 20px;
            font-size: 9px;
            color: #95a5a6;
        }

        .spacer {
            width: 4%;
        }

        .reason-box {
            margin-top: 15px;
            margin-bottom: 15px;
            padding: 10px 15px;
            background-color: #f0fdfa;
            border-left: 4px solid #0d9488;
            border-radius: 4px;
        }

        .reason-box .label {
            font-size: 9px;
            text-transform: uppercase;
            color: #0d9488;
            font-weight: bold;
        }

        .reason-box .text {
            font-size: 11px;
            color: #333;
            margin-top: 4px;
        }
    </style>
</head>

<body>

    <div class="container">
        <!-- HEADER -->
        <table class="header-table">
            <tr>
                <td>
                    <h1 class="report-title">Back To Warehouse Note</h1>
                </td>
                <td class="company-info">
                    TRANS KARGO SOLUSINDO<br>
                    <span style="font-size: 9px; font-weight: normal;">Warehouse Management System</span>
                </td>
            </tr>
        </table>

        <!-- INFO SECTION (2 COLUMNS) -->
        <table class="info-section">
            <tr>
                <!-- LEFT COLUMN -->
                <td class="info-column">
                    <table class="info-table">
                        <tr>
                            <td class="label">Reference No</td>
                            <td class="value">: {{ $backToWh->number }}</td>
                        </tr>
                        <tr>
                            <td class="label">Received At</td>
                            <td class="value">: {{ $backToWh->received_at ? \Carbon\Carbon::parse($backToWh->received_at)->format('d M Y') : '-' }}
                            </td>
                        </tr>
                        <tr>
                            <td class="label">Received By</td>
                            <td class="value">: {{ $backToWh->received_by ?? '-' }}</td>
                        </tr>
                    </table>
                </td>

                <!-- MIDDLE SPACER -->
                <td class="spacer"></td>

                <!-- RIGHT COLUMN -->
                <td class="info-column">
                    <table class="info-table">
                        <tr>
                            <td class="label">Recorded At</td>
                            <td class="value">: {{ \Carbon\Carbon::parse($backToWh->created_at)->format('d M Y H:i') }}
                            </td>
                        </tr>
                        <tr>
                            <td class="label">Handled By</td>
                            <td class="value">: {{ $backToWh->user->name ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="label">Remarks</td>
                            <td class="value">: {{ $backToWh->remarks ?? '-' }}</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        <!-- REASON BOX -->
        <div class="reason-box">
            <div class="label">Return Reason</div>
            <div class="text">{{ $backToWh->reason }}</div>
        </div>

        <!-- DATA TABLE -->
        <table class="data-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Part Name</th>
                    <th>Part Number</th>
                    <th>Serial Number</th>
                    <th>Condition</th>
                    <th>Reason</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($backToWhDetail as $index => $detail)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>{{ $detail->part_name }}</td>
                        <td>{{ $detail->part_number }}</td>
                        <td>{{ $detail->serial_number }}</td>
                        <td class="text-center">
                            <span class="badge badge-{{ strtolower($detail->condition) }}">{{ $detail->condition ?? '-' }}</span>
                        </td>
                        <td>{{ $detail->reason ?? '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- SIGNATURE SECTION -->
        <table style="width: 100%; margin-top: 50px; text-align: center;">
            <tr>
                <td style="width: 33%;">
                    <p style="margin-bottom: 60px;">Warehouse / Admin</p>
                    <p>( ____________________ )</p>
                </td>
                <td style="width: 33%;">
                    <p style="margin-bottom: 60px;">Logistic / Courier</p>
                    <p>( ____________________ )</p>
                </td>
                <td style="width: 33%;">
                    <p style="margin-bottom: 60px;">Receiver</p>
                    <p>( ____________________ )</p>
                </td>
            </tr>
        </table>

        <!-- FOOTER -->
        <div class="footer">
            Generated by WMS-TKS System at {{ now()->format('d M Y H:i:s') }}
        </div>
    </div>

    <script type="text/php">
        if (isset($pdf)) {
            $x = 520;
            $y = 820;
            $text = "Page {PAGE_NUM} of {PAGE_COUNT}";
            $font = $fontMetrics->get_font("helvetica", "normal");
            $size = 9;
            $color = array(0.6, 0.6, 0.6);
            $word_space = 0.0;
            $char_space = 0.0;
            $angle = 0.0;
            $pdf->page_text($x, $y, $text, $font, $size, $color, $word_space, $char_space, $angle);
        }
    </script>
</body>

</html>
