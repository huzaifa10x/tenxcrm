<x-guest-layout>
    <h3>Invoice #{{ $invoice->id }}</h3>
    <p>Client: {{ $invoice->client_name }}</p>
    <p>Issue Date: {{ $invoice->issue_date }}</p>
    <p>Due Date: {{ $invoice->due_date }}</p>

    <table width="100%" border="1" cellspacing="0" cellpadding="5">
        <thead>
            <tr>
                <th>Serial</th>
                <th>Description</th>
                <th>Qty</th>
                <th>Rate</th>
                <th>Tax %</th>
                <th>Amount</th>
            </tr>
        </thead>
        <tbody>
            @foreach (json_decode($invoice->items_json) as $item)
                <tr>
                    <td>{{ $item->serial }}</td>
                    <td>{{ $item->desc }}</td>
                    <td>{{ $item->qty }}</td>
                    <td>{{ $item->rate }}</td>
                    <td>{{ $item->tax }}</td>
                    <td>{{ $item->amount }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</x-guest-layout>
