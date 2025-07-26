<?php

namespace App\Jobs;

use App\Models\RadioStation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class CheckStationWorkingStatusJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 600;

    public $failOnTimeout = false;

    public $stationIds;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($stationIds)
    {
        $this->onQueue('station-checker');

        $this->stationIds = $stationIds;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        foreach ($this->stationIds as $stationId) {
            try {
                $station = RadioStation::findOrFail($stationId);

                $url = $station->url_resolved;

                if (empty($url)) {
                    $url = $station->url; // Fallback to url
                }

                if (empty($url)) {
                    $station->update(['is_working' => false]);
                    $station->delete();

                    Log::warning('Station Checker Job: ID{'.$stationId.'} No url found');

                    return;
                }

                $parsed_url = parse_url($url);

                $update_data = [
                    'last_checked_at' => now(),
                ];

                $parsed_url['scheme'] = 'https';
                $url = parsedUrlToString($parsed_url);

                if (isStationUrlWorking($url)) {
                    $update_data['is_working'] = true;
                    $update_data['url_resolved'] = $url;
                    $update_data['ssl_error'] = false;

                    $station->update($update_data);

                    Log::info('Station Checker Job: ID{'.$stationId.'} Working with https');

                    return;
                }

                $parsed_url['scheme'] = 'http';
                $url = parsedUrlToString($parsed_url);

                $update_data['is_working'] = isStationUrlWorking($url);
                $update_data['url_resolved'] = $url;

                Log::info('Station Checker Job: ID{'.$stationId.'} '.($update_data['is_working'] ? 'Working as non https' : 'Not Working'));

                $station->update($update_data);
            } catch (\Exception $exception) {
                Log::error('Station Checker Job: ID{'.$stationId.'} '.$exception->getMessage());
            }
        }
    }
}
