<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice #{{ $invoice->id }}</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; color: #333; }
        .box { padding: 20px; border: 1px solid #ddd; }
        .header { margin-bottom: 20px; }
        .table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        .table th, .table td { border-bottom: 1px solid #eee; padding: 8px; text-align: left; }
        .total { font-size: 16px; font-weight: bold; margin-top: 10px; }
    </style>
</head>
<body>
    <div class="box">
        <div class="header">
            <h2>{{ $invoice->merchant_name }}</h2>
            <p>Invoice #{{ $invoice->id }}</p>
            <p><strong>Customer:</strong> {{ $invoice->client_name }} ({{ $invoice->client_phone }})</p>
        </div>

        <table class="table">
            <thead>
                <tr><th>Item</th><th>Qty</th><th>Price</th></tr>
            </thead>
            <tbody>
                @foreach($invoice->items as $item)
                    <tr>
                        <td>{{ $item['name'] }}</td>
                        <td>{{ $item['qty'] }}</td>
                        <td>₹{{ $item['qty'] * $item['price'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="total">Total: ₹{{ $invoice->grand_total }}</div>
        <p style="margin-top:15px; color:#666;">Pay to UPI ID: <strong>{{ $invoice->upi_id }}</strong></p>
    </div>
</body>
</html>