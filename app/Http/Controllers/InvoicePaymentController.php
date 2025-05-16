<?php

namespace App\Http\Controllers;

use App\Models\InvoicePayment;
use Illuminate\Http\Request;

class InvoicePaymentController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'invoice_id' => 'required|exists:invoices,id',
            'amount' => 'required|numeric|min:0.01',
            'payment_date' => 'required|date',
        ]);

        $payment = InvoicePayment::create($request->only('invoice_id', 'amount', 'payment_date', 'payment_method', 'note'));

        $invoice = $payment->invoice;
        $invoice->is_paid = $invoice->is_fully_paid;
        $invoice->save();

        $payment->invoice->updatePaymentStatus();

        return response()->json(['success' => true, 'message' => 'Payment added']);
    }

    public function index($invoiceId)
    {
        $payments = InvoicePayment::where('invoice_id', $invoiceId)->get();
        return response()->json($payments);
    }
}
