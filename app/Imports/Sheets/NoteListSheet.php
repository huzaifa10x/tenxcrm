<?php

namespace App\Imports\Sheets;

use App\Imports\Concerns\MapsPipedriveColumns;
use App\Models\Crm\Deal;
use App\Models\Crm\Note;
use App\Models\User;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

/**
 * "note list" sheet.
 * Columns: ID | Content | Organization | Organization ID | Contact person |
 *          Contact person ID | Deal title | Deal ID | Add time | Update time |
 *          User | Note is pinned to deal | ...
 *
 * Notes are attached to the matching deal (by Pipedrive Deal ID). Notes that
 * belong to leads/projects rather than a deal in this module are skipped.
 */
class NoteListSheet implements ToCollection, WithHeadingRow, WithChunkReading
{
    use MapsPipedriveColumns;

    public function __construct(protected User $actor) {}

    public function collection(Collection $rows): void
    {
        foreach ($rows as $row) {
            $content = $this->pick($row, ['content']);
            $dealPd  = $this->pick($row, ['deal_id']);

            if (! $content || ! $dealPd) {
                continue; // need both a body and a deal to attach to
            }

            $deal = Deal::where('pipedrive_id', (int) $dealPd)->first();
            if (! $deal) {
                continue; // note belongs to a lead/project we didn't import
            }

            $notePd = $this->pick($row, ['id']);
            $user   = $this->resolveUserByLabel($this->pick($row, ['user'])) ?? $this->actor;
            $addTime = $this->datetime($this->pick($row, ['add_time']));

            $attributes = [
                'content'         => $content,
                'deal_id'         => $deal->id,
                'person_id'       => $deal->person_id,
                'organization_id' => $deal->organization_id,
                'user_id'         => $user->id,
                'pinned'          => $this->truthy($this->pick($row, ['note_is_pinned_to_deal'])),
            ];

            $note = $notePd
                ? Note::updateOrCreate(['pipedrive_id' => (int) $notePd], $attributes)
                : Note::create($attributes);

            if ($note->wasRecentlyCreated && $addTime) {
                $note->forceFill(['created_at' => $addTime])->saveQuietly();
            }
        }
    }

    public function chunkSize(): int
    {
        return 1000;
    }
}