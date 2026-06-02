<?php

namespace App\Imports\Sheets;

use App\Imports\Concerns\MapsPipedriveColumns;
use App\Models\Crm\Organization;
use App\Models\Crm\Person;
use App\Models\User;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

/**
 * "person list" sheet — the only place phone numbers and emails exist.
 * Columns: ID | Name | Label | Phone - Work/Home/Mobile/Other |
 *          Email - Work/Home/Other | Organization | Organization ID |
 *          Owner | First name | Last name | ...
 */
class PersonListSheet implements ToCollection, WithHeadingRow, WithChunkReading
{
    use MapsPipedriveColumns;

    public function __construct(protected User $actor) {}

    public function collection(Collection $rows): void
    {
        foreach ($rows as $row) {
            $pdId = $this->pick($row, ['id']);
            $name = $this->pick($row, ['name'])
                ?? trim(($this->pick($row, ['first_name']) ?? '') . ' ' . ($this->pick($row, ['last_name']) ?? ''));

            if (! $pdId && $name === '') {
                continue;
            }

            // Pipedrive splits contact methods across typed columns; take the first present.
            $phone = $this->pick($row, ['phone_work', 'phone_mobile', 'phone_home', 'phone_other']);
            $email = $this->pick($row, ['email_work', 'email_home', 'email_other']);

            $phoneType = match (true) {
                $this->pick($row, ['phone_work'])   !== null => 'work',
                $this->pick($row, ['phone_mobile']) !== null => 'mobile',
                $this->pick($row, ['phone_home'])   !== null => 'home',
                default => 'other',
            };
            $emailType = $this->pick($row, ['email_work']) !== null ? 'work'
                : ($this->pick($row, ['email_home']) !== null ? 'home' : 'other');

            $organization = $this->resolveOrganization($row);

            $attributes = [
                'name'            => $name !== '' ? $name : 'Unknown',
                'first_name'      => $this->pick($row, ['first_name']),
                'last_name'       => $this->pick($row, ['last_name']),
                'email'           => $email,
                'email_type'      => $emailType,
                'phone'           => $phone,
                'phone_type'      => $phoneType,
                'label'           => $this->pick($row, ['label', 'labels']),
                'organization_id' => $organization?->id,
                'owner_id'        => ($this->resolveUserByLabel($this->pick($row, ['owner'])) ?? $this->actor)->id,
            ];

            // Key on pipedrive_id when present, else fall back to name so a person
            // already created by the deal sheet gets enriched rather than duplicated.
            if ($pdId) {
                Person::updateOrCreate(['pipedrive_id' => (int) $pdId], $attributes);
            } else {
                Person::updateOrCreate(['name' => $attributes['name']], $attributes);
            }
        }
    }

    private function resolveOrganization($row): ?Organization
    {
        $orgPdId = $this->pick($row, ['organization_id']);
        $orgName = $this->pick($row, ['organization']);

        if (! $orgPdId && ! $orgName) {
            return null;
        }

        $key  = $orgPdId ? ['pipedrive_id' => (int) $orgPdId] : ['name' => $orgName];
        $data = array_filter([
            'pipedrive_id' => $orgPdId ? (int) $orgPdId : null,
            'name'         => $orgName ?? 'Unknown',
            'owner_id'     => $this->actor->id,
        ], fn ($v) => $v !== null);

        return Organization::updateOrCreate($key, $data);
    }

    public function chunkSize(): int
    {
        return 500;
    }
}