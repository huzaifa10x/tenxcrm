<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #{{ $invoice->id }}</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            color: #333;
        }

        table {
            width: 100%;
            margin: 0 auto;
            max-width: 650px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
            border-collapse: collapse;
        }

        td {
            padding: 16px;
            font-size: 16px;
            line-height: 1.5;
        }

        h1,
        h2,
        h3 {
            font-family: 'Arial', sans-serif;
            color: #2C3E50;
        }

        h1 {
            font-size: 36px;
            color: #a9e324;
            margin: 0;
        }

        h2 {
            font-size: 24px;
            color: #34495E;
        }

        .invoice-header {
            text-align: center;
            background-color: #f3ffd8;
            padding: 30px 20px;
            color: #fff;
            border-radius: 8px 8px 0 0;
        }

        .invoice-header img {
            max-height: 50px;
            margin-bottom: 15px;
        }

        .invoice-summary p {
            font-size: 16px;
            color: #34495E;
            margin: 5px 0;
        }

        .invoice-summary p strong {
            font-weight: bold;
        }

        .btn-view-invoice,
        .btn-download-pdf {
            display: inline-block;
            background-color: #a9e324;
            color: #fff;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 4px;
            margin-top: 20px;
            font-size: 16px;
            text-align: center;
        }

        .invoice-details th,
        .invoice-details td {
            text-align: left;
            padding: 12px;
            font-size: 14px;
            border-bottom: 1px solid #f1f1f1;
        }

        .invoice-details th {
            background-color: #f8f9fa;
            color: #34495E;
        }

        .invoice-details td {
            color: #7f8c8d;
        }

        .total-amount {
            font-size: 24px;
            color: #2C3E50;
            text-align: right;
            font-weight: bold;
        }

        .footer {
            background-color: #f1f1f1;
            text-align: center;
            padding: 20px;
            font-size: 14px;
            color: #7f8c8d;
        }

        .footer a {
            color: #a9e324;
            text-decoration: none;
        }

        .footer p {
            margin: 5px 0;
        }

        .hr-line {
            border: 0;
            border-top: 1px solid #dee2e6;
            margin: 40px 0;
        }
    </style>
</head>

<body>
    <table>
        <tr>
            <td class="invoice-header">
                <img src="{{ asset('/images/dark-logo.png') }}" alt="Company Logo">
                <h1>Invoice #{{ $invoice->id }}</h1>
                <p style="color: #2C3E50">{{ \Carbon\Carbon::parse($invoice->issue_date)->format('F Y') }} - AED
                    {{ number_format($invoice->grand_total, 2) }}</p>
            </td>
        </tr>

        <tr>
            <td class="invoice-summary">
                <p><strong>Dear {{ $invoice->client_name }},</strong></p>
                <p>We are pleased to present the invoice for your recent purchase from TEN X DIGITAL FZCO. Below are the
                    details of your invoice:</p>
                <p><strong>Invoice Number:</strong> INV-{{ $invoice->id }}</p>
                <p><strong>Issue Date:</strong> {{ \Carbon\Carbon::parse($invoice->issue_date)->format('d M Y') }}</p>
                @if ($invoice->is_paid != 1)
                    <p><strong>Due Date:</strong> {{ \Carbon\Carbon::parse($invoice->due_date)->format('d M Y') }}</p>
                    <p><strong>Total Amount:</strong> AED {{ number_format($invoice->grand_total, 2) }}</p>
                @endif
                <a href="{{ route('invoices.public', $invoice->uuid) }}" class="btn-view-invoice">View Invoice</a>
                {{-- <a href="{{ route('invoices.download', $invoice->uuid) }}" class="btn-download-pdf">Download as PDF</a> --}}
            </td>
        </tr>

        <tr>
            <td>
                <h2>Payment Instructions</h2>
                <p><strong>Cheque Payable to:</strong> "TENX DIGITAL FZCO"</p>
                <p><strong>Bank Transfer To:</strong><br>
                    Account Name: TENX DIGITAL FZCO<br>
                    Account Number: 1202 3174 920001<br>
                    IBAN: AE 7300300 1202 3174 920001<br>
                    Bank: ADCB
                </p>
                <p class="invoice-summary">Please ensure payment is made by the due date to avoid any late fees. If
                    payment has already been made, kindly disregard this message.</p>
            </td>
        </tr>

        <tr>
            <td>
                <h2>Invoice Breakdown</h2>
                <table class="invoice-details" width="100%">
                    <thead>
                        <tr>
                            <th>Description</th>
                            <th>Amount (AED)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($invoice->items as $item)
                            <tr>
                                <td>{{ $item->description }}</td>
                                <td>{{ number_format($item->amount, 2) }}</td>
                            </tr>
                        @endforeach
                        <tr>
                            <td><strong>Subtotal</strong></td>
                            <td>{{ number_format($invoice->total_amount, 2) }}</td>
                        </tr>
                        <tr>
                            <td><strong>VAT (5%)</strong></td>
                            <td>{{ number_format($invoice->tax_amount, 2) }}</td>
                        </tr>
                        <tr>
                            <td><strong>Payable Amount</strong></td>
                            <td>{{ number_format($invoice->grand_total, 2) }}</td>
                        </tr>
                    </tbody>
                </table>

                <h3 class="total-amount">
                    @if ($invoice->grand_total == 0)
                        No outstanding balance.
                    @else
                        Amount Due: AED {{ number_format(0, 2) }}
                    @endif
                </h3>
            </td>
        </tr>

        <tr>
            <td class="footer">
                <p>If you have any questions, please contact us at <a
                        href="mailto:info@tenxdigital.com">info@tenxdigital.com</a></p>
                <p>&copy; {{ \Carbon\Carbon::now()->year }} TEN X DIGITAL FZCO. All rights reserved.</p>
            </td>
        </tr>
    </table>
</body>

</html>
