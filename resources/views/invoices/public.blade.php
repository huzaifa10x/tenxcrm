<x-guest-layout>
    <h1>Invoice</h1>
    <p><strong>Client:</strong> {{ $invoice->client_name }}</p>
    <p><strong>Client:</strong> {{ $invoice->client_email }}</p>
    <p><strong>Issue Date:</strong> {{ $invoice->issue_date }}</p>
    <p><strong>Due Date:</strong> {{ $invoice->due_date }}</p>
    <hr>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Description</th>
                <th>Qty</th>
                <th>Rate</th>
                <th>Amount</th>
                <th>Tax</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($invoice->items as $item)
                <tr>
                    <td>{{ $item->serial_no }}</td>
                    <td>{{ $item->description }}</td>
                    <td>{{ $item->qty }}</td>
                    <td>{{ $item->rate }}</td>
                    <td>{{ $item->amount }}</td>
                    <td>{{ $item->tax }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <hr>
    <p><strong>Total:</strong> {{ $invoice->total_amount }}</p>
    <p><strong>Tax:</strong> {{ $invoice->tax_amount }}</p>
    <p><strong>Grand Total:</strong> {{ $invoice->grand_total }}</p>
    <p><strong>Notes:</strong> {{ $invoice->notes }}</p>

</x-guest-layout>
