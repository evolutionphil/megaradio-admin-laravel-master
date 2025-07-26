<?php

namespace App\Jobs;

use App\Models\Setting;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Throwable;

class DownloadStation implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $limit; // Pagination item limit

    public int $chunkIndex; // Pagination offset

    /**
     * Create a new job instance.
     *
     * @param  int  $chunkIndex
     * @param  int  $limit
     */
    public function __construct(int $chunkIndex = 0, int $limit = 500)
    {
        $this->onQueue('station-downloader');

        $this->chunkIndex = $chunkIndex;
        $this->limit = $limit;
    }

    /**
     * Execute the job.
     *
     * @return void
     *
     * @throws Throwable
     */
    public function handle(): void
    {
        Log::info('Downloading stations: '.($this->limit * $this->chunkIndex));

        $stations = $this->getStations();

        if (empty($stations)) {
            Log::info('No more stations.');

            $this->setLastSyncStoppedAt();

            return;
        }

        // Filter & Trim all string properties of each station
        $trimmedStations = collect($stations)
            ->filter(function ($station) {
                return ! empty(trim($station['name'])) && ! empty(trim($station['url_resolved']));
            })
            ->map(function ($station) {
                return collect($station)->map(function ($value) {
                    return is_string($value) ? trim($value) : $value;
                })->toArray();
            })
            ->toArray();

        $filename = "radio-browser/stations-$this->chunkIndex.json";

        Storage::disk('local')
            ->put($filename, json_encode($trimmedStations, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));

        Log::info('Downloaded '.count($stations).' stations.');

        ProcessDownloadedStation::dispatch($filename);
    }

    private function getStations(): array
    {
        $response = Http::get('http://all.api.radio-browser.info/json/stations', [
            'order' => 'votes',
            'reverse' => true,
            'hidebroken' => true,
            'limit' => $this->limit,
            'offset' => $this->chunkIndex * $this->limit,
        ]);

        return $response->json();
    }

    private function setLastSyncStoppedAt(): void
    {
        Setting::updateOrCreate(
            ['name' => 'radio-browser::last_sync_stopped_at'],
            ['value' => now()->toIso8601String()]
        );
    }
}
