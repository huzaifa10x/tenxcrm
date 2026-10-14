<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Lists duplicate attendance rows (same person, same date) and, once there are none, adds the
 * one-record-per-day unique index. Nothing is deleted automatically.
 */
class AddAttendanceUniqueIndex extends Command
{
    protected $signature = 'attendance:add-unique-index';

    protected $description = 'Report duplicate attendance rows and add the user/date unique index when clean';

    public function handle(): int
    {
        if (collect(Schema::getIndexes('attendances'))->contains(fn ($i) => $i['name'] === 'attendances_user_date_unique')) {
            $this->info('Unique index already present.');

            return self::SUCCESS;
        }

        $dupes = DB::table('attendances')
            ->select('user_id', 'date', DB::raw('COUNT(*) as n'), DB::raw('GROUP_CONCAT(id ORDER BY id) as ids'))
            ->groupBy('user_id', 'date')
            ->havingRaw('COUNT(*) > 1')
            ->get();

        if ($dupes->isNotEmpty()) {
            $this->warn('Duplicates found — keep the correct row for each and delete the others, then run this again:');
            $this->table(['user_id', 'date', 'rows', 'ids'], $dupes->map(fn ($d) => [$d->user_id, $d->date, $d->n, $d->ids])->all());

            return self::FAILURE;
        }

        Schema::table('attendances', fn (Blueprint $t) => $t->unique(['user_id', 'date'], 'attendances_user_date_unique'));
        $this->info('Unique index added.');

        return self::SUCCESS;
    }
}
