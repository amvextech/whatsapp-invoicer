<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WhatsApp Invoice Generator</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-4">

<div class="max-w-2xl mx-auto my-6 p-6 bg-white rounded-2xl shadow-xl border border-gray-100">
    <div class="mb-6 border-b pb-4">
        <h1 class="text-2xl font-bold text-gray-800">WhatsApp Invoice Generator</h1>
        <p class="text-xs text-gray-500">Create & Send WhatsApp Invoices with UPI Payments</p>
    </div>

    @if ($errors->any())
        <div class="mb-4 p-3 bg-red-100 text-red-700 text-xs rounded-lg">
            <ul class="list-disc pl-4">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('invoice.store') }}" method="POST" class="space-y-4">
        @csrf

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">Your Business / Name</label>
                <input type="text" name="merchant_name" value="{{ old('merchant_name') }}" required class="w-full border p-2.5 rounded-lg text-sm border-gray-300" placeholder="e.g. Acme Stores" />
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">Your UPI ID</label>
                <input type="text" name="upi_id" value="{{ old('upi_id') }}" required class="w-full border p-2.5 rounded-lg text-sm border-gray-300" placeholder="e.g. 9876543210@paytm" />
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">Customer Name</label>
                <input type="text" name="client_name" value="{{ old('client_name') }}" required class="w-full border p-2.5 rounded-lg text-sm border-gray-300" placeholder="e.g. Rahul Sharma" />
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">Customer WhatsApp No.</label>
                <input type="text" name="client_phone" value="{{ old('client_phone') }}" required class="w-full border p-2.5 rounded-lg text-sm border-gray-300" placeholder="10-digit number" />
            </div>
        </div>

        <hr class="my-4 border-gray-200">

        <div>
            <h3 class="text-sm font-semibold text-gray-700 mb-2">Line Items</h3>
            <div id="items-container">
                <div class="flex gap-2 items-center mb-3 item-row">
                    <input type="text" name="items[0][name]" placeholder="Item Name" required class="flex-1 border p-2 text-sm rounded-lg border-gray-300" />
                    <input type="number" name="items[0][qty]" placeholder="Qty" value="1" required class="w-20 border p-2 text-sm rounded-lg border-gray-300" />
                    <input type="number" name="items[0][price]" placeholder="Price (₹)" value="0" required class="w-28 border p-2 text-sm rounded-lg border-gray-300" />
                    <button type="button" onclick="removeRow(this)" class="text-red-500 font-bold px-2 text-sm">✕</button>
                </div>
            </div>

            <button type="button" onclick="addRow()" class="text-xs text-blue-600 font-semibold mt-1">+ Add Item</button>
        </div>

        <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-4 rounded-xl shadow-md transition duration-200 mt-4">
            Generate & Send on WhatsApp
        </button>
    </form>
</div>

<script>
    let itemIndex = 1;
    function addRow() {
        const container = document.getElementById('items-container');
        const row = document.createElement('div');
        row.className = 'flex gap-2 items-center mb-3 item-row';
        row.innerHTML = `
            <input type="text" name="items[${itemIndex}][name]" placeholder="Item Name" required class="flex-1 border p-2 text-sm rounded-lg border-gray-300" />
            <input type="number" name="items[${itemIndex}][qty]" placeholder="Qty" value="1" required class="w-20 border p-2 text-sm rounded-lg border-gray-300" />
            <input type="number" name="items[${itemIndex}][price]" placeholder="Price (₹)" value="0" required class="w-28 border p-2 text-sm rounded-lg border-gray-300" />
            <button type="button" onclick="removeRow(this)" class="text-red-500 font-bold px-2 text-sm">✕</button>
        `;
        container.appendChild(row);
        itemIndex++;
    }

    function removeRow(btn) {
        const rows = document.getElementsByClassName('item-row');
        if (rows.length > 1) {
            btn.parentElement.remove();
        }
    }
</script>

</body>
</html>