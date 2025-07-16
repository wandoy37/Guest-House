<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;

class InvoiceController extends Controller
{
    public function store(Request $request)
    {
        // Validate the request data
        $request->validate([
            'booking_id' => 'required|exists:bookings,id',
            'user_id' => 'required|exists:users,id',
        ]);

        // Generate invoice
        $count = Invoice::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count() + 1;

        $countFormatted = str_pad($count, 3, '0', STR_PAD_LEFT); // jadi 001, 002, dst
        $dateFormatted = now()->format('my'); // contoh: Juli 2025 → "0725"
        $invoiceNumber = $countFormatted . $dateFormatted;

        $dataInvoice = [
            'booking_id' => $request->booking_id,
            'user_id' => $request->user_id,
            'invoice_number' => $invoiceNumber,
        ];

        $invoice = Invoice::create($dataInvoice);
        // Simpan data ke session
        session()->flash('success', 'Invoice created successfully with number: ' . $invoiceNumber);
        session()->flash('open_invoice', route('invoice.show', ['invoice' => $invoice->invoice_number]));
        // session()->flash('open_invoice', route('invoice.show', ['invoice' => $invoice->id]));

        return redirect()->back();
    }

    public function show(Invoice $invoice)
    {
        // Load the invoice with related booking and guest data
        $invoice->load(['booking', 'guest']);

        return view('invoice', compact('invoice'));
    }
}
