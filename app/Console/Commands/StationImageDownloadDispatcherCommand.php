<?php

namespace App\Console\Commands;

use App\Jobs\DownloadStationImages;
use App\Models\RadioStation;
use Illuminate\Console\Command;

class StationImageDownloadDispatcherCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:radio:download-image {--chunk=20}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Dispatches jobs to download station image';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $query = RadioStation::where('is_working', true)
            ->where('favicon', 'like', 'http%');

        $query->select(['id'])
            ->chunkById($this->option('chunk'), function ($stations) {
                DownloadStationImages::dispatch($stations->pluck('id'));
            });

        $totalFoundStations = $query->count();

        $this->info('Total '.ceil($totalFoundStations / $this->option('chunk')).' job dispatched for '.$totalFoundStations.' stations.');

        gc_collect_cycles();

        return Command::SUCCESS;
    }
}
