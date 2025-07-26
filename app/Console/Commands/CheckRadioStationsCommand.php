<?php

namespace App\Console\Commands;

use App\Jobs\CheckStationWorkingStatusJob;
use App\Models\RadioStation;
use Illuminate\Console\Command;

class CheckRadioStationsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:radio:check-status
        {--chunk=20}
        {--only-non-working : Only take the stations that are not working}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Dispatches jobs to update working status of stations';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $query = RadioStation::query();

        if ($this->option('only-non-working')) {
            $query->where(function ($query) {
                $query
                    ->orWhere('is_working', 'exists', false)
                    ->orWhere('is_working', false);
            });
        } else {
            $query->where(function ($query) {
                $query
                    ->orWhere('last_checked_at', '<', now()->subDay())
                    ->orWhereNull('last_checked_at')
                    ->orWhere('is_working', 'exists', false)
                    ->orWhere('last_checked_at', 'exists', false);
            });
        }

        $query->select(['id'])
            ->chunkById($this->option('chunk'), function ($stations) {
                CheckStationWorkingStatusJob::dispatch($stations->pluck('id'));
            });

        $totalFoundStations = $query->count();

        $this->info('Total '.ceil($totalFoundStations / $this->option('chunk')).' job dispatched for '.$totalFoundStations.' stations.');

        gc_collect_cycles();

        return Command::SUCCESS;
    }
}
