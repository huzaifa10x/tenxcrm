<?php

namespace App\Imports\Sheets;

use App\Imports\Concerns\MapsPipedriveColumns;
use App\Models\Crm\Deal;
use App\Models\Crm\DealStageLog;
use App\Models\Crm\Organization;
use App\Models\Crm\Person;
use App\Models\Crm\Pipeline;
use App\Models\User;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

/**
 * "deal list" sheet.
 * Columns: ID | Title | Creator | Owner | Value | Currency of Value |
 *          Organization | Organization ID | Pipeline | Contact person |
 *          Contact person ID | Stage | Label | Status | Deal created |
 *          Last stage change | Last activity date | Won time | Lost time |
 *          Lost reason | Visible to | Expected close date | Source origin |
 *          Source channel | Source channel ID | ...
 *
 * Note: the deal sheet has NO phone/email — those come from the person sheet.
 */
class DealListSheet implements ToCollection, WithHeadingRow, WithChunkReading
{
    use MapsPipedriveColumns;

    public function __construct(protected User $actor) {}

    public function collection(Collection $rows): void
    {
        $pipeline   = Pipeline::default();
        $stages     = $pipeline->stages->keyBy(fn ($s) => strtolower($s->name));
        $firstStage = $pipeline->stages->first();

        foreach ($rows as $row) {
            $title = $this->pick($row, ['title', 'deal_title']);
            $pdId  = $this->pick($row, ['id']);
            if (! $title && ! $pdId) {
                continue;
            }

            $stage  = $stages->get(strtolower((string) $this->pick($row, ['stage']))) ?? $firstStage;
            $person = $this->resolvePerson($row);
            $org    = $this->resolveOrganization($row);
            $owner  = $this->resolveUserByLabel($this->pick($row, ['owner'])) ?? $this->actor;

            $statusRaw = strtolower((string) $this->pick($row, ['status']));
            $status    = in_array($statusRaw, ['open', 'won', 'lost'], true)
                ? $statusRaw
                : $stage->impliedStatus();

            $createdAt = $this->datetime($this->pick($row, ['deal_created']));

            $attributes = [
                'title'               => $title ?? 'Untitled deal',
                'value'               => $this->number($this->pick($row, ['value']) ?? 0),
                'currency'            => $this->pick($row, ['currency_of_value', 'currency']) ?? 'AED',
                'pipeline_id'         => $pipeline->id,
                'stage_id'            => $stage->id,
                'person_id'           => $person?->id,
                'organization_id'     => $org?->id,
                'owner_id'            => $owner->id,
                'created_by'          => $this->actor->id,
                'status'              => $status,
                'label'               => $this->pick($row, ['label']),
                'expected_close_date' => $this->date($this->pick($row, ['expected_close_date'])),
                'won_date'            => $this->datetime($this->pick($row, ['won_time'])),
                'lost_date'           => $this->datetime($this->pick($row, ['lost_time'])),
                'lost_reason'         => $this->pick($row, ['lost_reason']),
                'visible_to'          => $this->visibility($this->pick($row, ['visible_to'])),
                'source_origin'       => $this->pick($row, ['source_origin']) ?? 'Imported',
                'source_channel'      => $this->pick($row, ['source_channel']),
                'source_channel_id'   => $this->pick($row, ['source_channel_id']),
                'stage_changed_at'    => $this->datetime($this->pick($row, ['last_stage_change'])) ?? $createdAt ?? now(),
                'last_activity_at'    => $this->datetime($this->pick($row, ['last_activity_date'])) ?? now(),
            ];

            $key  = $pdId ? ['pipedrive_id' => (int) $pdId] : ['title' => $attributes['title'], 'owner_id' => $owner->id];
            $deal = Deal::updateOrCreate($key, $attributes);

            // Preserve the original Pipedrive creation date on first import.
            if ($deal->wasRecentlyCreated && $createdAt) {
                $deal->forceFill(['created_at' => $createdAt])->saveQuietly();
            }

            if ($person) {
                $deal->participants()->syncWithoutDetaching([$person->id]);
            }

            // Log the current stage only the first time we see this deal.
            if ($deal->wasRecentlyCreated) {
                DealStageLog::create([
                    'deal_id'    => $deal->id,
                    'stage_id'   => $stage->id,
                    'user_id'    => $this->actor->id,
                    'entered_at' => $attributes['stage_changed_at'],
                ]);
            }
        }
    }

    private function resolvePerson($row): ?Person
    {
        $pdId = $this->pick($row, ['contact_person_id']);
        $name = $this->pick($row, ['contact_person', 'person']);

        if (! $pdId && ! $name) {
            return null;
        }

        // Prefer linking by Pipedrive person ID (the person sheet fills in contact details).
        if ($pdId) {
            return Person::firstOrCreate(
                ['pipedrive_id' => (int) $pdId],
                ['name' => $name ?? 'Unknown', 'owner_id' => $this->actor->id]
            );
        }

        return Person::firstOrCreate(
            ['name' => $name],
            ['owner_id' => $this->actor->id]
        );
    }

    private function resolveOrganization($row): ?Organization
    {
        $pdId = $this->pick($row, ['organization_id']);
        $name = $this->pick($row, ['organization']);

        if (! $pdId && ! $name) {
            return null;
        }

        if ($pdId) {
            return Organization::firstOrCreate(
                ['pipedrive_id' => (int) $pdId],
                ['name' => $name ?? 'Unknown', 'owner_id' => $this->actor->id]
            );
        }

        return Organization::firstOrCreate(
            ['name' => $name],
            ['owner_id' => $this->actor->id]
        );
    }

    public function chunkSize(): int
    {
        return 500;
    }
}