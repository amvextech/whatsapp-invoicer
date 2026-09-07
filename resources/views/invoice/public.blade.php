<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #{{ $invoice->id }} - {{ $invoice->merchant_name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen p-4 flex items-center justify-center">

    <div class="max-w-md w-full bg-white rounded-2xl shadow-xl border border-gray-100 p-6">
        <!-- Header -->
        <div class="flex justify-between items-start border-b pb-4 mb-4">
            <div>
                <h1 class="text-xl font-bold text-gray-800">{{ $invoice->merchant_name }}</h1>
                <p class="text-xs text-gray-400">Invoice #{{ $invoice->id }}</p>
            </div>
            <span class="bg-yellow-100 text-yellow-800 text-xs px-2.5 py-1 rounded-full font-semibold">Payment Pending</span>
        </div>

        <!-- Billed To -->
        <div class="mb-4 text-xs text-gray-600">
            <span class="text-gray-400 block">Billed To:</span>
            <p class="font-bold text-gray-800 text-sm">{{ $invoice->client_name }}</p>
            <p>{{ $invoice->client_phone }}</p>
        </div>

        <!-- Items Table -->
        <table class="w-full text-xs text-left mb-6">
            <thead>
                <tr class="border-b text-gray-400">
                    <th class="py-2">Item</th>
                    <th class="text-center">Qty</th>
                    <th class="text-right">Price</th>
                </tr>
            </thead>
            <tbody>
                @foreach($invoice->items as $item)
                    <tr class="border-b border-gray-50">
                        <td class="py-2 font-medium text-gray-700">{{ $item['name'] }}</td>
                        <td class="text-center text-gray-500">{{ $item['qty'] }}</td>
                        <td class="text-right font-medium text-gray-700">₹{{ number_format($item['qty'] * $item['price'], 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- UPI QR Code & Total -->
        <div class="bg-gray-50 p-4 rounded-xl border border-gray-200 flex items-center justify-between mb-6">
            <div>
                <p class="text-xs text-gray-400 font-semibold">Total Amount Due</p>
                <p class="text-2xl font-black text-gray-900">₹{{ number_format($invoice->grand_total, 2) }}</p>
                <p class="text-[10px] text-gray-400 mt-1">Scan via GPay / PhonePe / Paytm</p>
            </div>
            <div class="bg-white p-2 rounded-lg border shadow-sm w-24 h-24 flex items-center justify-center">
                {!! $qrCode !!}
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="space-y-2">
            <!-- Mobile Direct Pay Button -->
            <a href="upi://pay?pa={{ $invoice->upi_id }}&pn={{ urlencode($invoice->merchant_name) }}&am={{ $invoice->grand_total }}&cu=INR" 
               class="sm:hidden block w-full text-center bg-green-600 hover:bg-green-700 text-white font-bold py-2.5 rounded-xl text-sm transition">
                Pay Direct via UPI App
            </a>

            <!-- Download PDF Button -->
            <a href="{{ route('invoice.download', $invoice->uuid) }}" 
               class="block w-full text-center bg-gray-900 hover:bg-gray-800 text-white font-bold py-2.5 rounded-xl text-sm transition">
                Download PDF
            </a>
        </div>
    </div>

</body>
</html>