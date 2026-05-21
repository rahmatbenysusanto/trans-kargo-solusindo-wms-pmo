<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Cycle Count Report</title>

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
            border-bottom: 2px solid #405189;
            margin-bottom: 15px;
            padding-bottom: 10px;
        }

        .header-table td {
            vertical-align: middle;
        }

        .report-title {
            font-size: 20px;
            font-weight: bold;
            color: #405189;
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
            background-color: #405189;
            color: #ffffff;
            text-align: center;
            font-weight: bold;
            padding: 8px 4px;
            text-transform: uppercase;
            font-size: 8px;
            border: 1px solid #405189;
        }

        .data-table td {
            padding: 6px 4px;
            border: 1px solid #e1e8ed;
            vertical-align: middle;
        }

        .data-table tr:nth-child(even) {
            background-color: #f4f6fa;
        }

        .text-center {
            text-align: center;
        }

        .badge {
            display: inline-block;
            padding: 2px 5px;
            border-radius: 3px;
            font-weight: bold;
            font-size: 8px;
            text-transform: uppercase;
        }

        .badge-success {
            background-color: #d4edda;
            color: #155724;
        }

        .badge-danger {
            background-color: #f8d7da;
            color: #721c24;
        }

        .badge-info {
            background-color: #d1ecf1;
            color: #0c5460;
        }

        .badge-secondary {
            background-color: #e2e3e5;
            color: #383d41;
        }

        .footer {
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px solid #e1e8ed;
            font-size: 8px;
            color: #95a5a6;
            text-align: center;
        }

        .storage-text {
            font-size: 8px;
            color: #7f8c8d;
        }

        .client-badge {
            background-color: #e8eaf6;
            color: #3f51b5;
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
                    <h1 class="report-title">Cycle Count Report</h1>
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
                    <td><strong>Total Logs:</strong> {{ count($history) }} Records</td>
                    <td style="text-align: right;"><strong>Date:</strong> {{ now()->format('d M Y H:i') }}</td>
                </tr>
            </table>
        </div>

        <!-- DATA TABLE -->
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width: 25px;">No</th>
                    <th style="width: 90px;">Date & Time</th>
                    <th>Product Information</th>
                    <th>Serial Number</th>
                    <th>Storage Location</th>
                    <th style="width: 60px;">Type</th>
                    <th>Description</th>
                    <th style="width: 80px;">Client</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($history as $index => $item)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td class="text-center">
                            <strong>{{ \Carbon\Carbon::parse($item->created_at)->format('d M Y') }}</strong><br>
                            <span style="color: #7f8c8d; font-size: 8px;">{{ \Carbon\Carbon::parse($item->created_at)->format('H:i:s') }}</span>
                        </td>
                        <td>
                            @if ($item->inventory)
                                <div style="font-weight: bold;">{{ $item->inventory->part_name }}</div>
                                <div style="color: #7f8c8d; font-size: 8px;">PN: {{ $item->inventory->part_number }}</div>
                            @else
                                <span style="color: #95a5a6; font-style: italic;">Product not found</span>
                            @endif
                        </td>
                        <td>
                            @if ($item->inventory)
                                <code style="color: #2e59d9; font-weight: bold;">{{ $item->inventory->serial_number }}</code>
                            @else
                                -
                            @endif
                        </td>
                        <td>
                            @if ($item->inventory && $item->inventory->bin)
                                <div style="font-weight: bold; color: #2c3e50;">{{ $item->inventory->bin->name }}</div>
                                <div class="storage-text">
                                    {{ $item->inventory->bin->storageArea->name }} > {{ $item->inventory->bin->storageRak->name }}
                                </div>
                            @else
                                <span style="color: #95a5a6; font-style: italic;">Not Assigned</span>
                            @endif
                        </td>
                        <td class="text-center">
                            @php
                                $badgeClass = 'badge-secondary';
                                if ($item->type == 'Inbound') {
                                    $badgeClass = 'badge-success';
                                } elseif ($item->type == 'Outbound') {
                                    $badgeClass = 'badge-danger';
                                } elseif ($item->type == 'Movement') {
                                    $badgeClass = 'badge-info';
                                }
                            @endphp
                            <span class="badge {{ $badgeClass }}">{{ $item->type }}</span>
                        </td>
                        <td>{{ $item->description }}</td>
                        <td class="text-center">
                            @if ($item->inventory && $item->inventory->client)
                                <span class="client-badge">{{ $item->inventory->client->name }}</span>
                            @else
                                -
                            @endif
                        </td>
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
