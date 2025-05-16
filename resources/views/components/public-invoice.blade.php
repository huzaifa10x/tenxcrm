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
            color: #333;
            padding: 0;
        }

        .paid-banner {
            color: white;
            text-align: center;
            padding: 5px 16px;
            font-size: 16px;
            display: flex;
            font-weight: bold;
            text-transform: uppercase;
            align-items: center;
            justify-content: space-evenly;
        }

        .paid-banner p {
            margin: 0;
        }

        .paid-banner.green {
            background-color: #2ecc71;
        }

        .paid-banner.red {
            background-color: #ff0000;
        }

        .wrapper {
            max-width: 70%;
            position: relative;
            transform: translateX(-50%);
            left: 50%;
        }

        table {
            width: 100%;
            margin: 0 auto;
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
            background-color: #f3ffd8;
            padding: 30px 20px;
            color: #fff;
            border-radius: 8px 8px 0 0;
        }

        .invoice-header img {
            max-height: 50px;
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

        .paid {
            width: 130px;
            position: absolute;
            right: 20px;
            top: 20px;
        }

        @media screen and (max-width: 780px) {
            .wrapper {
                max-width: 100%;
            }

            .paid {
                width: 100px;
            }
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <img style="width: 200px" src="https://10xksa.com/images/dark-logo.png" alt="Company Logo">
        <div class="paid-banner @if ($invoice->is_paid) green @else red @endif">
            @if ($invoice->is_paid)
                <p>Paid</p>
            @else
                <p>Unpaid</p>
            @endif
        </div>
        <table>
            <tr>
                <td class="invoice-header">
                    <h1>Invoice #{{ $invoice->id }}</h1>
                    <p style="color: #2C3E50">
                        {{ \Carbon\Carbon::parse($invoice->issue_date)->format('F Y') }} - AED
                        {{ number_format($invoice->grand_total, 2) }}
                    </p>
                </td>
            </tr>

            <tr>
                <td class="invoice-summary">
                    <p><strong>Dear {{ $invoice->client_name }},</strong></p>
                    <p>We are pleased to present the invoice for your recent engagement with 10X DIGITAL. Below
                        are the details of your invoice:</p>
                    <p><strong>Invoice Number:</strong> INV-{{ $invoice->id }}</p>
                    <p><strong>Issue Date:</strong> {{ \Carbon\Carbon::parse($invoice->issue_date)->format('d M Y') }}
                    </p>

                    @if ($invoice->is_paid != 1)
                        <p><strong>Due Date:</strong> {{ \Carbon\Carbon::parse($invoice->due_date)->format('d M Y') }}
                        </p>
                        <p><strong>Total Amount:</strong> AED {{ number_format($invoice->grand_total, 2) }}</p>
                    @endif
                    @if ($isEmail == 1)
                        <a href="{{ route('invoices.public', $invoice->uuid) }}" class="btn-view-invoice">View
                            Invoice</a>
                    @endif
                </td>
            </tr>

            <tr>
                <td>
                    <h2>Payment Instructions</h2>
                    <p><strong>Cheque Payable to:</strong> 10X DIGITAL</p>
                    <p><strong>Bank Transfer To:</strong><br>
                        Account Name: 10X DIGITAL<br>
                        Account Number: 1202 3174 920001<br>
                        IBAN: AE730030012023174920001<br>
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
                                <td><strong>{{ $invoice->is_paid != 1 ? 'Payable Amount' : 'Amount Paid' }}</strong>
                                </td>
                                <td>{{ number_format($invoice->grand_total, 2) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>

            @if ($invoice->payments->count())
                <tr>
                    <td>
                        <h2>Payments Made</h2>
                        <table class="invoice-details" width="100%">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Method</th>
                                    <th>Amount (AED)</th>
                                    <th>Note</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($invoice->payments as $payment)
                                    <tr>
                                        <td>{{ \Carbon\Carbon::parse($payment->paid_at)->format('d M Y') }}</td>
                                        <td>{{ ucfirst($payment->payment_method ?? 'N/A') }}</td>
                                        <td>{{ number_format($payment->amount, 2) }}</td>
                                        <td>{{ $payment->note ?? '-' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </td>
                </tr>

                @if ($invoice->is_paid != 1)
                    <tr>
                        <td>
                            <h3>Outstanding Balance</h3>
                            <p><strong>Remaining:</strong> AED
                                {{ number_format($invoice->grand_total - $invoice->payments->sum('amount'), 2) }}
                            </p>
                        </td>
                    </tr>
                @endif
            @endif

            <tr>
                <td class="footer">
                    <p>If you have any questions, please contact us at <a
                            href="mailto:info@tenxdigital.com">info@tenxdigital.com</a></p>
                    <p>&copy; {{ now()->year }} 10X DIGITAL. All rights reserved.</p>
                </td>
            </tr>
        </table>
    </div>
</body>

</html>
