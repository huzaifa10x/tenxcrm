<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\InvoiceItem;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Mail\InvoiceMail;
use Illuminate\Support\Facades\Mail;

class InvoiceController extends Controller
{
    public function index()
    {
        $invoices = Invoice::latest()->get();
        return view('invoices.index', compact('invoices'));
    }
    public function updatePaidStatus(Request $request)
    {
        $request->validate([
            'invoice_id' => 'required|exists:invoices,id',
            'is_paid' => 'required|boolean',
        ]);

        $invoice = Invoice::find($request->invoice_id);
        $invoice->is_paid = $request->is_paid;
        $invoice->save();

        return response()->json(['success' => true, 'message' => 'Status updated successfully.']);
    }


    public function downloadPdf($id)
    {
        $invoice = Invoice::with('items')->findOrFail($id);
        $pdf = PDF::loadView('invoices.pdf', compact('invoice'));
        return $pdf->download("invoice_{$invoice->id}.pdf");
    }
    public function edit($id)
    {
        $invoice = Invoice::with('items')->findOrFail($id);
        return response()->json(['invoice' => $invoice]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'client_name' => 'required|string',
            'client_email' => 'required|email',
            'issue_date' => 'required|date',
            'due_date' => 'required|date',
            'items_json' => 'required|json',
        ]);

        $invoice = Invoice::findOrFail($id);

        $invoice->update([
            'client_name' => $request->client_name,
            'client_email' => $request->client_email,
            'issue_date' => $request->issue_date,
            'due_date' => $request->due_date,
            'notes' => $request->notes,
            'grand_total' => collect(json_decode($request->items_json))->sum('amount'),
        ]);

        // Clear old items
        $invoice->items()->delete();

        // Create new items
        foreach (json_decode($request->items_json, true) as $item) {
            $invoice->items()->create([
                'serial_no' => $item['serial'],
                'description' => $item['desc'],
                'qty' => $item['qty'],
                'rate' => $item['rate'],
                'tax' => $item['tax'],
                'amount' => $item['amount'],
            ]);
        }

        return response()->json(['message' => 'Invoice updated successfully.']);
    }

    public function destroy($id)
    {
        Invoice::findOrFail($id)->delete();
        return response()->json(['message' => 'Invoice deleted']);
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_name' => 'required|string',
            'client_email' => 'required|string|email',
            'issue_date' => 'required|date',
            'due_date' => 'required|date',
            'notes' => 'nullable|string',
            'items_json' => 'required|string',
        ]);

        $items = json_decode($validated['items_json'], true);

        if (!is_array($items) || count($items) === 0) {
            return response()->json(['error' => 'No invoice items provided.'], 422);
        }

        $invoice = Invoice::create([
            'uuid' => Str::uuid(),
            'client_name' => $validated['client_name'],
            'client_email' => $validated['client_email'],
            'issue_date' => $validated['issue_date'],
            'due_date' => $validated['due_date'],
            'notes' => $validated['notes'] ?? null,
            'total_amount' => 0,
            'tax_amount' => 0,
            'grand_total' => 0,
        ]);

        $total = 0;
        $tax = 0;

        foreach ($items as $index => $item) {
            $qty = floatval($item['qty'] ?? 0);
            $rate = floatval($item['rate'] ?? 0);
            $amount = $qty * $rate;
            $itemTax = floatval($item['tax'] ?? 0);
            $taxAmount = ($amount * $itemTax) / 100;
            $grand = $amount + $taxAmount;

            InvoiceItem::create([
                'invoice_id' => $invoice->id,
                'serial_no' => $item['serial'] ?? ($index + 1),
                'description' => $item['desc'] ?? '',
                'qty' => $qty,
                'rate' => $rate,
                'amount' => $amount,
                'tax' => $itemTax,
            ]);

            $total += $amount;
            $tax += $taxAmount;
        }

        $invoice->update([
            'total_amount' => $total,
            'tax_amount' => $tax,
            'grand_total' => $total + $tax,
        ]);

        return response()->json([
            'message' => 'Invoice created',
            'uuid' => $invoice->uuid
        ]);
    }
    public function showPublic($uuid)
    {
        $invoice = Invoice::with('items')->where('uuid', $uuid)->firstOrFail();
        return view('invoices.public', compact('invoice'));
    }
    public function sendEmail($id)
    {
        $invoice = Invoice::findOrFail($id);

        if (!$invoice->client_email) {
            return response()->json(['message' => 'Client email not found'], 400);
        }

        Mail::to($invoice->client_email)->send(new InvoiceMail($invoice));

        return response()->json(['message' => 'The Invoice ' . $invoice->uuid . ' has been sent to ' . $invoice->client_email . ' successfully']);
    }
    public function emailCheckView()
    {
        $invoice = Invoice::first();
        return view('emails.invoice', compact('invoice'));
    }
}
