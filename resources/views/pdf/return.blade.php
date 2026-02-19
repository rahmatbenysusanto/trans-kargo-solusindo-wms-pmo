<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Return Report</title>

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
            border-bottom: 2px solid #444;
            margin-bottom: 20px;
            padding-bottom: 10px;
        }

        .header-table td {
            vertical-align: middle;
        }

        .report-title {
            font-size: 22px;
            font-weight: bold;
            color: #c0392b;
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
            background-color: #2c3e50;
            color: #ffffff;
            text-align: center;
            font-weight: bold;
            padding: 10px 8px;
            text-transform: uppercase;
            font-size: 10px;
            border: 1px solid #2c3e50;
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

        .badge-danger {
            background-color: #fdeaea;
            color: #c0392b;
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
    </style>
</head>

<body>

    <div class="container">
        <!-- HEADER -->
        <table class="header-table">
            <tr>
                <td>
                    <h1 class="report-title">Return Report</h1>
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
                            <td class="value">: {{ $outbound->number }}</td>
                        </tr>
                        <tr>
                            <td class="label">Client Name</td>
                            <td class="value">: {{ $outbound->client->name }}</td>
                        </tr>
                        <tr>
                            <td class="label">Return Date</td>
                            <td class="value">: {{ \Carbon\Carbon::parse($outbound->delivery_date)->format('d M Y') }}
                            </td>
                        </tr>
                        <tr>
                            <td class="label">Transaction Type</td>
                            <td class="value">: <span class="badge badge-danger">RETURN</span></td>
                        </tr>
                        <tr>
                            <td class="label">Site Location</td>
                            <td class="value">: {{ $outbound->site_location }}</td>
                        </tr>
                    </table>
                </td>

                <!-- MIDDLE SPACER -->
                <td class="spacer"></td>

                <!-- RIGHT COLUMN -->
                <td class="info-column">
                    <table class="info-table">
                        <tr>
                            <td class="label">Returned By</td>
                            <td class="value">: {{ $outbound->received_by ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="label">Logistics / Courier</td>
                            <td class="value">: {{ $outbound->courier ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="label">Tracking ID</td>
                            <td class="value">: {{ $outbound->tracking_number ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="label">Recorded At</td>
                            <td class="value">: {{ \Carbon\Carbon::parse($outbound->created_at)->format('d M Y H:i') }}
                            </td>
                        </tr>
                        <tr>
                            <td class="label">Notes</td>
                            <td class="value">: {{ $outbound->remarks ?? '-' }}</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        <!-- DATA TABLE -->
        <table class="data-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Part Name</th>
                    <th>Part Number</th>
                    <th>Serial Number</th>
                    <th>Condition</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($outboundDetail as $index => $product)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>{{ $product->inventory->part_name }}</td>
                        <td>{{ $product->inventory->part_number }}</td>
                        <td>{{ $product->inventory->serial_number }}</td>
                        <td class="text-center">
                            <span class="badge"
                                style="background-color: #f8f9fa; color: #333; border: 1px solid #ddd;">
                                {{ $product->condition }}
                            </span>
                        </td>
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
                    <p style="margin-bottom: 60px;">Authorized Receiver</p>
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
            $x = 750;
            $y = 565;
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
