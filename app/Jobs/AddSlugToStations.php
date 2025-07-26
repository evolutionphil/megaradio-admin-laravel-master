<?php

namespace App\Jobs;

use App\Models\Genre;
use App\Models\RadioStation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class AddSlugToStations implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 0;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        $this->onQueue('station-downloader');
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $total = 0;

        RadioStation::whereNull('slug')
            ->withTrashed()
            ->orderBy('is_working', 'desc')
            ->orderBy('votes', 'desc')
            ->orderBy('clickcount', 'desc')
            ->chunk(1000, function ($items) use (&$total) {
                $total += count($items);

                foreach ($items as $item) {
                    $item->update([
                        'slug' => RadioStation::generateSlug($item['name']),
                    ]);
                }
            });

        echo 'Total Stations: '.$total.PHP_EOL;

        $total = 0;

        Genre::whereNull('slug')
            ->orderBy('total_stations', 'desc')
            ->chunk(1000, function ($items) use (&$total) {
                $total += count($items);

                foreach ($items as $item) {
                    $item->update([
                        'slug' => Genre::generateSlug($item['name']),
                    ]);
                }
            });

        echo 'Total Genres: '.$total.PHP_EOL;
    }
}
