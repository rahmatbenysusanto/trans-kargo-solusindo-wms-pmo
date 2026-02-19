<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Inventory Report</title>

    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 9px;
            color: #333;
            line-height: 1.4;
            margin: 0;
            padding: 0;
        }

        .container {
            padding: 20px;
        }

        .header-table {
            width: 100%;
            border-bottom: 2px solid #27ae60;
            margin-bottom: 15px;
            padding-bottom: 10px;
        }

        .header-table td {
            vertical-align: middle;
        }

        .report-title {
            font-size: 20px;
            font-weight: bold;
            color: #27ae60;
            margin: 0;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .company-info {
            text-align: right;
            font-size: 11px;
            font-weight: bold;
            color: #7f8c8d;
        }

        .summary-box {
            background-color: #f8f9fa;
            border: 1px solid #e1e8ed;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            border-radius: 5px;
            overflow: hidden;
        }

        .data-table th {
            background-color: #27ae60;
            color: #ffffff;
            text-align: center;
            font-weight: bold;
            padding: 8px 4px;
            text-transform: uppercase;
            font-size: 8px;
            border: 1px solid #27ae60;
        }

        .data-table td {
            padding: 6px 4px;
            border: 1px solid #e1e8ed;
            vertical-align: middle;
        }

        .data-table tr:nth-child(even) {
            background-color: #f1f8f4;
        }

        .text-center {
            text-align: center;
        }

        .footer {
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px solid #e1e8ed;
            font-size: 8px;
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

        .storage-text {
            font-size: 8px;
            color: #7f8c8d;
        }

        .client-badge {
            background-color: #e8f5e9;
            color: #2e7d32;
            padding: 2px 5px;
            border-radius: 3px;
            font-weight: bold;
            font-size: 8px;
        }
    </style>
</head>

<body>

    <div class="container">
        <!-- HEADER -->
        <table class="header-table">
            <tr>
                <td>
                    <h1 class="report-title">Inventory Stock Report</h1>
                </td>
                <td class="company-info">
                    TRANS KARGO SOLUSINDO<br>
                    <span style="font-size: 8px; font-weight: normal;">Warehouse Management System</span>
                </td>
            </tr>
        </table>

        <!-- SUMMARY -->
        <div class="summary-box">
            <table style="width: 100%;">
                <tr>
                    <td><strong>Total Items:</strong> {{ count($inventory) }} Assets</td>
                    <td style="text-align: right;"><strong>Date:</strong> {{ now()->format('d M Y H:i') }}</td>
                </tr>
            </table>
        </div>

        <!-- DATA TABLE -->
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width: 25px;">No</th>
                    <th>Storage Location</th>
                    <th>Client / Owner</th>
                    <th>Asset Details</th>
                    <th>Serial Number</th>
                    <th>Condition</th>
                    <th>MFG Date</th>
                    <th>Warranty</th>
                    <th>EOS Date</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($inventory as $index => $product)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>
                            <div style="font-weight: bold; color: #2c3e50;">{{ $product->bin->name }}</div>
                            <div class="storage-text">
                                {{ $product->bin->storageArea->name }} > {{ $product->bin->storageRak->name }}
                            </div>
                        </td>
                        <td class="text-center">
                            <span class="client-badge">{{ $product->inboundDetail->inbound->client->name }}</span>
                        </td>
                        <td>
                            <div style="font-weight: bold;">{{ $product->part_name }}</div>
                            <div style="color: #7f8c8d; font-size: 8px;">PN: {{ $product->part_number }}</div>
                        </td>
                        <td><code style="color: #d35400;">{{ $product->serial_number }}</code></td>
                        <td class="text-center">{{ strtoupper($product->condition) }}</td>
                        <td class="text-center">{{ $product->manufacture_date ?: '-' }}</td>
                        <td class="text-center">{{ $product->warranty_end_date ?: '-' }}</td>
                        <td class="text-center">{{ $product->eos_date ?: '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
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
