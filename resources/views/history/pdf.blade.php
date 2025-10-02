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
    <p>
        User: <strong>{{ $authUser->name }}</strong><br>
        Period:
        @if(request('start_datetime') && request('end_datetime'))
            @php
                $start = \Carbon\Carbon::parse(request('start_datetime'));
                $end = \Carbon\Carbon::parse(request('end_datetime'));

                $shift = ($start->format('H') >= 10 && $end->format('H') <= 22) ? 'Morning' : 'Night';
            @endphp

            {{ $start->format('d/m/Y H:i') }} - {{ $end->format('d/m/Y H:i') }}<br>
            Shift: <strong>{{ $shift }}</strong>
        @else
            - 
        @endif
    </p>
    
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
                    <td>{{ $row->created_at->timezone('Asia/Makassar')->format('d/m/Y H:i') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h3 style="margin-top:20px;">Summary</h3>
    <table>
        <tbody>
            {{-- Makanan --}}
            <tr>
                <th colspan="2">Makanan</th>
            </tr>
            <tr>
                <td>Total Metode QRIS</td>
                <td>{{ $jumlahFoodQris }}</td>
            </tr>
            <tr>
                <td>Total Pembayaran QRIS</td>
                <td>{{ number_format($totalFoodQris, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Total Metode Tunai</td>
                <td>{{ $jumlahFoodTunai }}</td>
            </tr>
            <tr>
                <td>Total Pembayaran Tunai</td>
                <td>{{ number_format($totalFoodTunai, 0, ',', '.') }}</td>
            </tr>

            {{-- Billing --}}
            <tr>
                <th colspan="2" style="padding-top:10px;">Billing</th>
            </tr>
            <tr>
                <td>Total Metode QRIS</td>
                <td>{{ $jumlahBillingQris }}</td>
            </tr>
            <tr>
                <td>Total Pembayaran QRIS</td>
                <td>{{ number_format($totalBillingQris, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Total Metode Tunai</td>
                <td>{{ $jumlahBillingTunai }}</td>
            </tr>
            <tr>
                <td>Total Pembayaran Tunai</td>
                <td>{{ number_format($totalBillingTunai, 0, ',', '.') }}</td>
            </tr>

            {{-- Subtotal --}}
            <tr>
                <th colspan="2" style="padding-top:10px;">Subtotal</th>
            </tr>
            <tr>
                <td>Subtotal Metode QRIS</td>
                <td>{{ $jumlahFoodQris + $jumlahBillingQris }}</td>
            </tr>
            <tr>
                <td>Subtotal Pembayaran QRIS</td>
                <td>{{ number_format($totalFoodQris + $totalBillingQris, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Subtotal Metode Tunai</td>
                <td>{{ $jumlahFoodTunai + $jumlahBillingTunai }}</td>
            </tr>
            <tr>
                <td>Subtotal Pembayaran Tunai</td>
                <td>{{ number_format($totalFoodTunai + $totalBillingTunai, 0, ',', '.') }}</td>
            </tr>

            {{-- Grand Total --}}
            <tr>
                <th>Total</th>
                <th>{{ number_format(($totalFoodQris + $totalBillingQris) + ($totalFoodTunai + $totalBillingTunai), 0, ',', '.') }}</th>
            </tr>
        </tbody>
    </table>
</body>

</html>