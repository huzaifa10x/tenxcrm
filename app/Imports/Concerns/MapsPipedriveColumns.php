<?php

namespace App\Imports\Concerns;

use Carbon\Carbon;
use Illuminate\Support\Collection;

/**
 * Shared helpers for reading a Pipedrive export row.
 *
 * WithHeadingRow lower-cases + slugs the header into snake_case keys
 * ("Currency of Value" -> "currency_of_value", "Phone - Work" -> "phone_work"),
 * but we look up by a list of candidate keys so the importer keeps working
 * even if Pipedrive tweaks a header.
 */
trait MapsPipedriveColumns
{
    /** First non-empty value among the given candidate keys, else null. */
    protected function pick($row, array $keys): ?string
    {
        foreach ($keys as $key) {
            $val = $row[$key] ?? null;
            if ($val !== null && trim((string) $val) !== '') {
                return trim((string) $val);
            }
        }
        return null;
    }

    /** Strip currency symbols / thousands separators down to a float. */
    protected function number($v): float
    {
        return (float) preg_replace('/[^0-9.\-]/', '', (string) $v);
    }

    /** Parse a Pipedrive date or datetime string (or Excel serial) to Y-m-d. */
    protected function date($v): ?string
    {
        if ($v === null || trim((string) $v) === '') {
            return null;
        }
        try {
            if (is_numeric($v)) {
                return \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($v)->format('Y-m-d');
            }
            return Carbon::parse($v)->format('Y-m-d');
        } catch (\Throwable $e) {
            return null;
        }
    }

    /** Parse to a full datetime (used for created/won/lost timestamps). */
    protected function datetime($v): ?string
    {
        if ($v === null || trim((string) $v) === '') {
            return null;
        }
        try {
            if (is_numeric($v)) {
                return \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($v)->format('Y-m-d H:i:s');
            }
            return Carbon::parse($v)->format('Y-m-d H:i:s');
        } catch (\Throwable $e) {
            return null;
        }
    }

    protected function truthy($v): bool
    {
        return in_array(strtolower(trim((string) $v)), ['1', 'yes', 'true', 'y'], true);
    }

    /** Pipedrive "Visible to" -> our owner|everyone flag. */
    protected function visibility($v): string
    {
        $s = strtolower((string) $v);
        return (str_contains($s, 'everyone') || str_contains($s, 'company') || str_contains($s, 'all'))
            ? 'everyone' : 'owner';
    }

    /** Match a Pipedrive owner label to a local user by name or email. */
    protected function resolveUserByLabel(?string $label): ?\App\Models\User
    {
        if (! $label) {
            return null;
        }
        return \App\Models\User::where('email', $label)
            ->orWhere('name', $label)
            ->first();
    }
}