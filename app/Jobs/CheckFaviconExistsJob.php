<?php

namespace App\Jobs;

use App\Models\RadioStation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class CheckFaviconExistsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 0;

    private $stationIds = [];

    /**
     * Create a new job instance.
     */
    public function __construct($stationIds)
    {
        $this->onQueue('station-checker');

        $this->stationIds = $stationIds;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        foreach ($this->stationIds as $stationId) {
            $station = RadioStation::find($stationId);

            if ($station === null) {
                continue;
            }

            $faviconExists = Storage::exists($station->favicon);

            if ($faviconExists === false) {
                echo "Favicon does not exist for station: {$station->name}\n";
                $station->update([
                    'favicon' => null,
                    'has_uploaded_favicon' => false,
                ]);
            } else {
                echo "Favicon exists for station: {$station->name}\n";
            }
        }
    }
}
