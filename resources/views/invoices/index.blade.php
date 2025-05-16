<x-app-layout>
    @if (auth()->user()->hasRole('SALES MANAGER') ||
            auth()->user()->hasRole('ADMIN') ||
            auth()->user()->hasRole('Accountant'))
        <x-slot name="header">
            {{ __('Invoices') }}
        </x-slot>
        <div class="container">
            <button class="btn btn-primary mb-3" id="addInvoiceBtn">Add Invoice</button>
            <table class="table table-bordered" id="invoiceTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Client</th>
                        <th>Email</th>
                        <th>Paid / Unpaid</th>
                        <th>Issue Date</th>
                        <th>Due Date</th>
                        <th>Total</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($invoices as $invoice)
                        <tr data-id="{{ $invoice->id }}">
                            <td>{{ $invoice->id }}</td>
                            <td>{{ $invoice->client_name }}</td>
                            <td>{{ $invoice->client_email }}</td>
                            <td>
                                <select class="form-select form-select-sm change-paid-status"
                                    data-id="{{ $invoice->id }}">
                                    <option value="0" {{ !$invoice->is_paid ? 'selected' : '' }}>Unpaid</option>
                                    <option value="1" {{ $invoice->is_paid ? 'selected' : '' }}>Paid</option>
                                </select>
                            </td>
                            <td>{{ $invoice->issue_date }}</td>
                            <td>{{ $invoice->due_date }}</td>
                            <td>{{ $invoice->grand_total }}</td>
                            <td>
                                <button class="btn btn-sm btn-info editInvoiceBtn">Edit</button>
                                <button class="btn btn-sm btn-danger deleteInvoiceBtn">Delete</button>
                                <button class="btn btn-sm btn-info send-email" data-id="{{ $invoice->id }}">Send
                                    Email</button>
                                <a href="{{ route('invoices.public', $invoice->uuid) }}" target="_blank"
                                    class="btn btn-sm btn-secondary">View</a>
                                <button class="btn btn-sm btn-success add-payment"
                                    data-id="{{ $invoice->id }}">Payment</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="modal fade" id="invoiceModal" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <form id="invoiceForm">
                    @csrf
                    <input type="hidden" id="invoice_id" name="invoice_id">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="invoiceModalLabel"></h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body row">
                            <div class="col-md-6 mb-3">
                                <label>Client Name</label>
                                <input type="text" name="client_name" id="client_name" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Client Email</label>
                                <input type="text" name="client_email" id="client_email" class="form-control"
                                    required>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label>Issue Date</label>
                                <input type="date" name="issue_date" id="issue_date" class="form-control" required>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label>Due Date</label>
                                <input type="date" name="due_date" id="due_date" class="form-control" required>
                            </div>
                            <div class="col-md-12 mb-3">
                                <label>Notes</label>
                                <textarea name="notes" id="notes" class="form-control"></textarea>
                            </div>
                            <div class="table-responsive mb-3">
                                <table class="table table-bordered" id="itemsTable">
                                    <thead>
                                        <tr>
                                            <th>Serial</th>
                                            <th>Description</th>
                                            <th>Qty</th>
                                            <th>Rate</th>
                                            <th>Tax %</th>
                                            <th>Amount</th>
                                            <th><button type="button" class="btn btn-sm btn-success"
                                                    id="addItemRow">+</button></th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                            <input type="hidden" name="items_json" id="items_json">

                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-success">Save Invoice</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="modal fade" id="paymentModal" tabindex="-1">
            <div class="modal-dialog">
                <form id="paymentForm">
                    @csrf
                    <input type="hidden" name="invoice_id" id="payment_invoice_id">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Add Payment</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label>Amount</label>
                                <input type="number" name="amount" class="form-control" required step="0.01"
                                    min="0.01">
                            </div>
                            <div class="mb-3">
                                <label>Payment Date</label>
                                <input type="date" name="payment_date" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label>Payment Method</label>
                                <input type="text" name="payment_method" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label>Note</label>
                                <textarea name="note" class="form-control"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-success">Save Payment</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <script>
            const createInvoiceRoute = `{{ route('invoices.store') }}`;
            const updateInvoiceRouteTemplate = `{{ route('invoices.update', ['invoice' => 'INVOICE_ID']) }}`;

            $(function() {

                $(document).on('change', '.change-paid-status', function() {
                    const invoiceId = $(this).data('id');
                    const isPaid = $(this).val();

                    $.ajax({
                        url: "{{ route('invoices.updatePaidStatus') }}",
                        type: "POST",
                        data: {
                            invoice_id: invoiceId,
                            is_paid: isPaid,
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (response.success) {
                                toastr.success(response.message); // If using toastr
                            } else {
                                alert('Failed to update.');
                            }
                        },
                        error: function() {
                            alert('Error occurred.');
                        }
                    });
                });

                $(document).on('click', '.send-email', function() {
                    const invoiceId = $(this).data('id');

                    if (!confirm('Send this invoice by email?')) return;

                    $.ajax({
                        url: `{{ route('invoices.sendEmail', ['id' => 'INVOICE_ID']) }}`.replace(
                            'INVOICE_ID', invoiceId),
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        beforeSend: function() {
                            toastr.info('Sending invoice...');
                        },
                        success: function(res) {
                            toastr.success(res.message || 'Invoice sent!');
                        },
                        error: function(err) {
                            toastr.error('Failed to send invoice.');
                        }
                    });
                });

                $('#addInvoiceBtn').on('click', function() {
                    $('#invoiceForm')[0].reset();
                    $('#invoiceModalLabel').text('Add Invoice');
                    $('#invoice_id').val('');
                    $('#invoiceModal').modal('show');
                });

                $('#invoiceForm').on('submit', function(e) {
                    e.preventDefault();

                    const formData = $(this).serialize();
                    const id = $('#invoice_id').val();

                    const url = id ?
                        updateInvoiceRouteTemplate.replace('INVOICE_ID', id) :
                        createInvoiceRoute;

                    const type = id ? 'PUT' : 'POST';

                    $.ajax({
                        url: url,
                        type: type,
                        data: formData,
                        success: function() {
                            location.reload();
                        },
                        error: function(err) {
                            console.log(err.responseJSON);
                        }
                    });
                });

                $('.editInvoiceBtn').on('click', function() {
                    const row = $(this).closest('tr');
                    const id = row.data('id');
                    $.get(`{{ route('invoices.edit', ['id' => 'INVOICE_ID']) }}`.replace('INVOICE_ID', id),
                        function(
                            data) {
                            $('#invoiceModalLabel').text('Edit Invoice');
                            $('#invoice_id').val(data.invoice.id);
                            $('#client_name').val(data.invoice.client_name);
                            $('#client_email').val(data.invoice.client_email);
                            $('#notes').val(data.invoice.notes);

                            flatpickr("#issue_date", {
                                enableTime: false,
                                dateFormat: "Y-m-d",
                                altInput: true,
                                altFormat: "F j, Y",
                                allowInput: true,
                                defaultDate: data.invoice.issue_date ? data.invoice.issue_date :
                                    null // Set date from URL if exists
                            });
                            flatpickr("#due_date", {
                                enableTime: false,
                                dateFormat: "Y-m-d",
                                altInput: true,
                                altFormat: "F j, Y",
                                allowInput: true,
                                defaultDate: data.invoice.due_date ? data.invoice.due_date :
                                    null // Set date from URL if exists
                            });

                            const items = data.invoice.items || [];
                            const tbody = $('#itemsTable tbody');
                            tbody.empty(); // clear old rows

                            items.forEach(item => {
                                tbody.append(`
                        <tr>
                            <td><input type="text" class="form-control serial" value="${item.serial_no}" /></td>
                            <td><input type="text" class="form-control desc" value="${item.description}" /></td>
                            <td><input type="number" class="form-control qty" value="${item.qty}" /></td>
                            <td><input type="number" class="form-control rate" value="${item.rate}" /></td>
                            <td><input type="number" class="form-control tax" value="${item.tax}" /></td>
                            <td><input type="text" class="form-control amount" readonly value="${(item.qty * item.rate * (1 + item.tax / 100)).toFixed(2)}" /></td>
                            <td><button type="button" class="btn btn-sm btn-danger removeRow">×</button></td>
                        </tr>
                    `);
                            });

                            recalculateAmounts(); // update JSON and amount fields

                            $('#invoiceModal').modal('show');
                        });
                });

                $('.deleteInvoiceBtn').on('click', function() {
                    if (!confirm('Are you sure?')) return;
                    const id = $(this).closest('tr').data('id');

                    $.ajax({
                        url: `/invoices/${id}`,
                        type: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function() {
                            location.reload();
                        }
                    });
                });
            });

            // PAYMENTS WORK
            $(document).on('click', '.add-payment', function() {
                const invoiceId = $(this).data('id');
                $('#payment_invoice_id').val(invoiceId);
                $('#paymentForm')[0].reset();
                $('#paymentModal').modal('show');
            });

            $('#paymentForm').on('submit', function(e) {
                e.preventDefault();
                $.ajax({
                    url: `{{ route('invoice-payments.store') }}`,
                    method: 'POST',
                    data: $(this).serialize(),
                    success: function(res) {
                        toastr.success(res.message);
                        $('#paymentModal').modal('hide');
                        location.reload();
                    },
                    error: function(err) {
                        toastr.error('Error saving payment');
                        console.log(err.responseJSON);
                    }
                });
            });

            // PAYMENTS WORK

            // ITEMS WORK

            function recalculateAmounts() {
                let items = [];
                $('#itemsTable tbody tr').each(function() {
                    const row = $(this);
                    const serial = row.find('.serial').val();
                    const desc = row.find('.desc').val();
                    const qty = parseFloat(row.find('.qty').val()) || 0;
                    const rate = parseFloat(row.find('.rate').val()) || 0;
                    const tax = parseFloat(row.find('.tax').val()) || 0;
                    const amount = qty * rate;
                    const totalWithTax = amount + (amount * tax / 100);

                    row.find('.amount').val(totalWithTax.toFixed(2));

                    items.push({
                        serial,
                        desc,
                        qty,
                        rate,
                        tax,
                        amount: totalWithTax
                    });
                });
                $('#items_json').val(JSON.stringify(items));
            }

            $('#addItemRow').on('click', function() {
                $('#itemsTable tbody').append(`
                <tr>
                    <td><input type="text" class="form-control serial" /></td>
                    <td><input type="text" class="form-control desc" /></td>
                    <td><input type="number" class="form-control qty" /></td>
                    <td><input type="number" class="form-control rate" /></td>
                    <td><input type="number" class="form-control tax" /></td>
                    <td><input type="text" class="form-control amount" readonly /></td>
                    <td><button type="button" class="btn btn-sm btn-danger removeRow">×</button></td>
                </tr>
            `);
            });

            $(document).on('input', '.qty, .rate, .tax', recalculateAmounts);

            $(document).on('click', '.removeRow', function() {
                $(this).closest('tr').remove();
                recalculateAmounts();
            });

            $('#invoiceForm').on('submit', function() {
                recalculateAmounts();
            });
        </script>
    @else
        @include('vendor.not-allowed')
    @endif
</x-app-layout>
