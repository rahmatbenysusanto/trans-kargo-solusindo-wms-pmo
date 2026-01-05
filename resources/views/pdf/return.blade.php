<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Return Report</title>

    <style>
        body {
            font-family: DejaVu Sans;
            font-size: 11px;
            color: #333;
        }

        .header {
            text-align: center;
            margin-bottom: 15px;
        }

        .header h2 {
            margin: 0;
            font-size: 16px;
        }

        .info-table {
            width: 100%;
            margin-bottom: 15px;
        }

        .info-table td {
            padding: 4px 6px;
            vertical-align: top;
        }

        .info-table td.label {
            width: 120px;
            font-weight: bold;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
        }

        .data-table th,
        .data-table td {
            border: 1px solid #000;
            padding: 6px;
        }

        .data-table th {
            background-color: #f2f2f2;
            text-align: center;
            font-weight: bold;
        }

        .data-table td {
            vertical-align: middle;
        }

        .text-center {
            text-align: center;
        }

        .footer {
            margin-top: 20px;
            font-size: 10px;
            text-align: right;
        }
    </style>
</head>
<body>

<!-- HEADER -->
<div class="header">
    <h2>RETURN REPORT</h2>
</div>

<!-- INFO -->
<table class="info-table">
    <tr>
        <td class="label">Number</td>
        <td>:</td>
        <td>{{ $outbound->number }}</td>
    </tr>
    <tr>
        <td class="label">Client</td>
        <td>:</td>
        <td>{{ $outbound->client->name }}</td>
    </tr>
    <tr>
        <td class="label">Site Location</td>
        <td>:</td>
        <td>{{ $outbound->site_location }}</td>
    </tr>
    <tr>
        <td class="label">Inbound Type</td>
        <td>:</td>
        <td>{{ $outbound->type }}</td>
    </tr>
    <tr>
        <td class="label">Delivery Date</td>
        <td>:</td>
        <td>{{ $outbound->delivery_date }}</td>
    </tr>
    <tr>
        <td class="label">Received By</td>
        <td>:</td>
        <td>{{ $outbound->received_by }}</td>
    </tr>
    <tr>
        <td class="label">Courier</td>
        <td>:</td>
        <td>{{ $outbound->courier }}</td>
    </tr>
    <tr>
        <td class="label">Tracking Number</td>
        <td>:</td>
        <td>{{ $outbound->tracking_number }}</td>
    </tr>
    <tr>
        <td class="label">Remarks</td>
        <td>:</td>
        <td>{{ $outbound->remarks }}</td>
    </tr>
    <tr>
        <td class="label">Created Date</td>
        <td>:</td>
        <td>{{ $outbound->created_at }}</td>
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
    @foreach($outboundDetail as $index => $product)
        <tr>
            <td class="text-center">{{ $index + 1 }}</td>
            <td>{{ $product->inventory->part_name }}</td>
            <td>{{ $product->inventory->part_number }}</td>
            <td>{{ $product->inventory->serial_number }}</td>
            <td>{{ $product->condition }}</td>
        </tr>
    @endforeach
    </tbody>
</table>

<!-- FOOTER -->
<div class="footer">
    Generated at {{ now()->format('d M Y H:i') }}
</div>

</body>
</html>
