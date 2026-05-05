<?php

namespace App\Models\Xero;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class XeroClient extends Model
{
    use SoftDeletes;

    protected $table = 'xero_clients';

    protected $fillable = [
        'xero_contact_id',
        'name',
        'email',
        'phone',
        'city',
        'country',
        'currency_code',
        'is_customer',
        'is_supplier',
        'status',
        'xero_updated_at',
    ];

    protected $casts = [
        'is_customer'     => 'boolean',
        'is_supplier'     => 'boolean',
        'xero_updated_at' => 'datetime',
    ];

    // ── Relationships ─────────────────────────────────────────────

    public function invoices(): HasMany
    {
        return $this->hasMany(XeroInvoice::class, 'xero_client_id');
    }

    public function payments(): HasManyThrough
    {
        return $this->hasManyThrough(
            XeroPayment::class,
            XeroInvoice::class,
            'xero_client_id',
            'invoice_id'
        );
    }
    // ── Scopes ────────────────────────────────────────────────────

    public function scopeActive($query)
    {
        return $query->where('status', 'ACTIVE');
    }

    // ── Helpers ───────────────────────────────────────────────────

    public static function findByXeroId(string $xeroId): ?self
    {
        return static::where('xero_contact_id', $xeroId)->first();
    }

    public static function upsertFromXero(array $contact): self
    {
        return static::updateOrCreate(
            ['xero_contact_id' => $contact['ContactID']],
            [
                'name'            => $contact['Name'],
                'email'           => $contact['EmailAddress'] ?? null,
                'phone'           => static::extractPhone($contact),
                'city'            => static::extractCity($contact),
                'country'         => static::extractCountry($contact),
                'currency_code'   => $contact['DefaultCurrency'] ?? 'AED',
                'is_customer'     => $contact['IsCustomer'] ?? false,
                'is_supplier'     => $contact['IsSupplier'] ?? false,
                'status'          => $contact['ContactStatus'] ?? 'ACTIVE',
                'xero_updated_at' => static::parseXeroDate($contact['UpdatedDateUTC'] ?? null),
            ]
        );
    }

    private static function extractPhone(array $contact): ?string
    {
        foreach ($contact['Phones'] ?? [] as $phone) {
            if ($phone['PhoneType'] === 'DEFAULT' && !empty($phone['PhoneNumber'])) {
                return $phone['PhoneNumber'];
            }
        }
        foreach ($contact['Phones'] ?? [] as $phone) {
            if (!empty($phone['PhoneNumber'])) {
                return $phone['PhoneNumber'];
            }
        }
        return null;
    }

    private static function extractCity(array $contact): ?string
    {
        foreach ($contact['Addresses'] ?? [] as $addr) {
            if ($addr['AddressType'] === 'POBOX' && !empty($addr['City'])) {
                return $addr['City'];
            }
        }
        return null;
    }

    private static function extractCountry(array $contact): ?string
    {
        foreach ($contact['Addresses'] ?? [] as $addr) {
            if (!empty($addr['Country'])) {
                return $addr['Country'];
            }
        }
        return null;
    }

    public static function parseXeroDate(?string $dateStr): ?Carbon
    {
        if (!$dateStr) return null;
        // Xero format: /Date(1623839712117+0000)/
        if (preg_match('/\/Date\((\d+)([+-]\d{4})?\)\//', $dateStr, $m)) {
            return Carbon::createFromTimestampMs((int) $m[1]);
        }
        try {
            return Carbon::parse($dateStr);
        } catch (\Exception $e) {
            return null;
        }
    }
}
