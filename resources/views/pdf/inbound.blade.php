<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Inbound Report</title>

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
    <h2>INBOUND REPORT</h2>
</div>

<!-- INFO -->
<table class="info-table">
    <tr>
        <td class="label">Number</td>
        <td>:</td>
        <td>{{ $inbound->number }}</td>
    </tr>
    <tr>
        <td class="label">Client</td>
        <td>:</td>
        <td>{{ $inbound->client->name }}</td>
    </tr>
    <tr>
        <td class="label">Site Location</td>
        <td>:</td>
        <td>{{ $inbound->site_location }}</td>
    </tr>
    <tr>
        <td class="label">Inbound Type</td>
        <td>:</td>
        <td>{{ $inbound->inbound_type }}</td>
    </tr>
    <tr>
        <td class="label">Owner Status</td>
        <td>:</td>
        <td>{{ $inbound->owner_status }}</td>
    </tr>
    <tr>
        <td class="label">Created Date</td>
        <td>:</td>
        <td>{{ $inbound->created_at }}</td>
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
        <th>Manufacture Date</th>
        <th>Warranty End</th>
        <th>EOS Date</th>
    </tr>
    </thead>
    <tbody>
    @foreach($inboundDetail as $index => $product)
        <tr>
            <td class="text-center">{{ $index + 1 }}</td>
            <td>{{ $product->part_name }}</td>
            <td>{{ $product->part_number }}</td>
            <td>{{ $product->serial_number }}</td>
            <td class="text-center">{{ strtoupper($product->condition) }}</td>
            <td class="text-center">{{ $product->manufacture_date }}</td>
            <td class="text-center">{{ $product->warranty_end_date }}</td>
            <td class="text-center">{{ $product->eos_date }}</td>
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
