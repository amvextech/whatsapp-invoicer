<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
class InvoiceController extends Controller
{
    // Show the HTML creation form
    public function create()
    {
        return view('invoice.create');
    }

    // Process form submission & redirect to WhatsApp
    public function store(Request $request)
    {
        $request->validate([
            'merchant_name' => 'required|string|max:255',
            'upi_id' => 'required|string|max:255',
            'client_name' => 'required|string|max:255',
            'client_phone' => 'required|numeric',
            'items' => 'required|array|min:1',
            'items.*.name' => 'required|string',
            'items.*.qty' => 'required|numeric|min:1',
            'items.*.price' => 'required|numeric|min:0',
        ]);

        // Calculate Grand Total
        $grandTotal = collect($request->items)->sum(function ($item) {
            return $item['qty'] * $item['price'];
        });

        // Save Invoice to Database
        $invoice = Invoice::create([
            'uuid' => (string) Str::uuid(),
            'merchant_name' => $request->merchant_name,
            'upi_id' => $request->upi_id,
            'client_name' => $request->client_name,
            'client_phone' => $request->client_phone,
            'items' => $request->items,
            'grand_total' => $grandTotal,
        ]);

        // Build WhatsApp share URL
        $invoiceUrl = route('invoice.public', $invoice->uuid);
        $message = urlencode("Hello {$invoice->client_name}, here is your invoice from {$invoice->merchant_name} for ₹{$grandTotal}: {$invoiceUrl}\n\nPlease scan the UPI QR code in the link to complete payment.");
        $phone = preg_replace('/[^0-9]/', '', $request->client_phone);

        return redirect()->to("https://wa.me/91{$phone}?text={$message}");
    }

    // Helper for UPI QR string
private function getUpiQr($invoice)
{
    $upiUri = "upi://pay?pa={$invoice->upi_id}&pn=" . urlencode($invoice->merchant_name) . "&am={$invoice->grand_total}&cu=INR";
    
    // Generates raw SVG string directly
    return QrCode::size(120)->generate($upiUri);
}

// Public link where client sees the bill & UPI QR
public function showPublic($uuid)
{
    $invoice = Invoice::where('uuid', $uuid)->firstOrFail();
    $qrCode = $this->getUpiQr($invoice);

    return view('invoice.public', compact('invoice', 'qrCode'));
}
    // Download PDF version
    public function downloadPdf($uuid)
    {
        $invoice = Invoice::where('uuid', $uuid)->firstOrFail();
        $qrCode = $this->getUpiQr($invoice);

        $pdf = Pdf::loadView('pdf.invoice', compact('invoice', 'qrCode'));
        return $pdf->download("Invoice-{$invoice->id}.pdf");
    }
}
