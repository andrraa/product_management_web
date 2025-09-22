<!DOCTYPE html>
<html>

<head>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 6px;
            text-align: left;
        }

        th {
            background: #f3f3f3;
        }
    </style>
</head>

<body>
    <h2>History Report</h2>
    <p>Period: {{ request('start_date') }} - {{ request('end_date') }}</p>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Type</th>
                <th>Qty</th>
                <th>Payment</th>
                <th>Price</th>
                <th>Total</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach($histories as $i => $row)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $row->name }}</td>
                    <td>{{ strtoupper($row->type) }}</td>
                    <td>{{ $row->quantity }}</td>
                    <td>{{ strtoupper($row->payment_method) }}</td>
                    <td>{{ number_format($row->price, 0, ',', '.') }}</td>
                    <td>{{ number_format($row->total_price, 0, ',', '.') }}</td>
                    <td>{{ $row->created_at->format('d/m/Y H:i') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h3 style="margin-top:20px;">Summary</h3>
    <table>
        <tbody>
            <tr>
                <td>Total QRIS Transactions</td>
                <td>{{ $jumlahQris }}</td>
            </tr>
            <tr>
                <td>Total QRIS Payment</td>
                <td>{{ number_format($totalQris, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Total TUNAI Transactions</td>
                <td>{{ $jumlahTunai }}</td>
            </tr>
            <tr>
                <td>Total TUNAI Payment</td>
                <td>{{ number_format($totalTunai, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <th>Grand Total</th>
                <th>{{ number_format($grandTotal, 0, ',', '.') }}</th>
            </tr>
        </tbody>
    </table>
</body>

</html>