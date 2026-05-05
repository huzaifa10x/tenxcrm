<?php

namespace App\Models\Xero;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class XeroInvoice extends Model
{
    use SoftDeletes;

    protected $table = 'xero_invoices';

    protected $fillable = [
        'xero_invoice_id', 'invoice_number', 'reference', 'type',
        'xero_client_id', 'xero_contact_id',
        'invoice_date', 'due_date', 'fully_paid_on_date',
        'currency_code', 'currency_rate',
        'sub_total', 'total_tax', 'total',
        'amount_due', 'amount_paid', 'amount_credited',
        'status', 'line_amount_types',
        'sent_to_contact', 'is_discounted',
        'xero_updated_at',
    ];

    protected $casts = [
        'invoice_date'       => 'date',
        'due_date'           => 'date',
        'fully_paid_on_date' => 'date',
        'xero_updated_at'    => 'datetime',
        'sent_to_contact'    => 'boolean',
        'is_discounted'      => 'boolean',
        'sub_total'          => 'decimal:2',
        'total_tax'          => 'decimal:2',
        'total'              => 'decimal:2',
        'amount_due'         => 'decimal:2',
        'amount_paid'        => 'decimal:2',
        'amount_credited'    => 'decimal:2',
        'currency_rate'      => 'decimal:8',
    ];

    // ── Relationships ─────────────────────────────────────────────

    public function client(): BelongsTo
    {
        return $this->belongsTo(XeroClient::class, 'xero_client_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(XeroInvoiceItem::class, 'invoice_id');
    }

    public function payments(): HasMany
    {
        return $this->hasMany(XeroPayment::class, 'invoice_id');
    }

    // ── Scopes ────────────────────────────────────────────────────

    public function scopePaid($query)
    {
        return $query->where('status', 'PAID');
    }

    public function scopeAuthorised($query)
    {
        return $query->whereIn('status', ['AUTHORISED', 'PAID']);
    }

    public function scopeSales($query)
    {
        return $query->where('type', 'ACCREC');
    }

    public function scopeDateRange($query, $from, $to)
    {
        return $query->whereBetween('invoice_date', [$from, $to]);
    }

    public function scopeForMonth($query, int $year, int $month)
    {
        return $query->whereYear('invoice_date', $year)
                     ->whereMonth('invoice_date', $month);
    }

    // ── Upsert from Xero API payload ──────────────────────────────

    public static function upsertFromXero(array $inv, int $clientId): self
    {
        $parseDate = fn($d) => $d ? XeroClient::parseXeroDate($d)?->toDateString() : null;

        $invoice = static::updateOrCreate(
            ['xero_invoice_id' => $inv['InvoiceID']],
            [
                'invoice_number'     => $inv['InvoiceNumber'] ?? null,
                'reference'          => $inv['Reference'] ?? null,
                'type'               => $inv['Type'] ?? 'ACCREC',
                'xero_client_id'     => $clientId,
                'xero_contact_id'    => $inv['Contact']['ContactID'] ?? '',
                'invoice_date'       => $parseDate($inv['DateString'] ?? $inv['Date'] ?? null),
                'due_date'           => $parseDate($inv['DueDateString'] ?? $inv['DueDate'] ?? null),
                'fully_paid_on_date' => $parseDate($inv['FullyPaidOnDate'] ?? null),
                'currency_code'      => $inv['CurrencyCode'] ?? 'AED',
                'currency_rate'      => $inv['CurrencyRate'] ?? 1,
                'sub_total'          => $inv['SubTotal'] ?? 0,
                'total_tax'          => $inv['TotalTax'] ?? 0,
                'total'              => $inv['Total'] ?? 0,
                'amount_due'         => $inv['AmountDue'] ?? 0,
                'amount_paid'        => $inv['AmountPaid'] ?? 0,
                'amount_credited'    => $inv['AmountCredited'] ?? 0,
                'status'             => $inv['Status'] ?? 'DRAFT',
                'line_amount_types'  => $inv['LineAmountTypes'] ?? 'EXCLUSIVE',
                'sent_to_contact'    => $inv['SentToContact'] ?? false,
                'is_discounted'      => $inv['IsDiscounted'] ?? false,
                'xero_updated_at'    => XeroClient::parseXeroDate($inv['UpdatedDateUTC'] ?? null),
            ]
        );

        if (!empty($inv['LineItems'])) {
            $invoice->syncLineItems($inv['LineItems']);
        }

        if (!empty($inv['Payments'])) {
            $invoice->syncPayments($inv['Payments']);
        }

        return $invoice;
    }

    public function syncLineItems(array $lineItems): void
    {
        $this->items()->delete();

        foreach ($lineItems as $line) {
            if (empty($line['LineItemID']) && empty($line['Description'])) continue;

            $resolved = XeroServiceMapping::resolveForItem(
                $line['ItemCode'] ?? null,
                $line['ItemCode'] ?? null,
                $line['Description'] ?? null
            );

            XeroInvoiceItem::create([
                'invoice_id'         => $this->id,
                'xero_item_id'       => null,
                'item_code'          => $line['ItemCode'] ?? null,
                'description'        => $line['Description'] ?? null,
                'account_code'       => $line['AccountCode'] ?? null,
                'quantity'           => $line['Quantity'] ?? 1,
                'unit_price'         => $line['UnitAmount'] ?? 0,
                'discount_rate'      => $line['DiscountRate'] ?? 0,
                'line_amount'        => $line['LineAmount'] ?? 0,
                'tax_amount'         => $line['TaxAmount'] ?? 0,
                'tax_type'           => $line['TaxType'] ?? null,
                'line_item_id'       => $line['LineItemID'] ?? null,
                'department_id'      => $resolved['department_id'],
                'normalized_service' => $resolved['normalized_service'],
            ]);
        }
    }

    public function syncPayments(array $payments): void
    {
        foreach ($payments as $pmt) {
            XeroPayment::updateOrCreate(
                ['xero_payment_id' => $pmt['PaymentID']],
                [
                    'invoice_id'      => $this->id,
                    'xero_invoice_id' => $this->xero_invoice_id,
                    'payment_date'    => XeroClient::parseXeroDate($pmt['DateString'] ?? $pmt['Date'] ?? null)?->toDateString(),
                    'amount'          => $pmt['Amount'] ?? 0,
                    'currency_code'   => 'AED',
                    'reference'       => $pmt['Reference'] ?? null,
                    'payment_type'    => $pmt['PaymentType'] ?? null,
                    'status'          => $pmt['Status'] ?? 'AUTHORISED',
                    'xero_updated_at' => XeroClient::parseXeroDate($pmt['UpdatedDateUTC'] ?? null),
                ]
            );
        }
    }
}