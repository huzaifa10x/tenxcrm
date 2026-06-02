<?php

namespace App\Imports;

use App\Imports\Sheets\DealListSheet;
use App\Imports\Sheets\NoteListSheet;
use App\Imports\Sheets\PersonListSheet;
use App\Models\User;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

/**
 * Imports a native Pipedrive export workbook.
 *
 * The export ships three sheets — "deal list", "person list", "note list" —
 * and each carries different columns (phones/emails live on the PERSON sheet,
 * not the deal sheet). We hand each sheet to a dedicated importer and link the
 * records together by Pipedrive's own IDs (stored in the `pipedrive_id` column).
 *
 * Re-importing the same file updates existing rows instead of duplicating them.
 */
class PipedriveDealsImport implements WithMultipleSheets
{
    public function __construct(protected User $actor) {}

    public function sheets(): array
    {
        return [
            // Persons first so phone/email is on record before deals link to them,
            // though linking is by pipedrive_id so order is not actually critical.
            'person list' => new PersonListSheet($this->actor),
            'deal list'   => new DealListSheet($this->actor),
            'note list'   => new NoteListSheet($this->actor),
        ];
    }
}