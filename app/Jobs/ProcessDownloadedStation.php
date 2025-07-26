<?php

namespace App\Jobs;

use App\Models\Country;
use App\Models\Genre;
use App\Models\RadioStation;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use MongoDB\BSON\ObjectId;

class ProcessDownloadedStation implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $filename;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($filename)
    {
        $this->onQueue('station-processor');

        $this->filename = $filename;
    }

    private function log($message)
    {
        if (config('app.env') == 'local') {
            echo $message.PHP_EOL;
        } else {
            Log::info("Processing stations from $this->filename");
        }
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if ($this->batch() && $this->batch()->cancelled()) {
            // Determine if the batch has been cancelled...

            return;
        }

        if (! Storage::disk('local')->exists($this->filename)) {
            Log::error('Job Cancelled '.$this->job->getJobId()." File not found at $this->filename");
            $this->delete();
        }

        $this->log("Processing stations from $this->filename");

        $stations = json_decode(Storage::disk('local')->get($this->filename), true);

        $this->log('Found '.count($stations).' stations.');

        foreach ($stations as $rawStationData) {
            $dbTags = collect();

            if (! empty($rawStationData['tags'])) {
                $tags = collect(explode(',', $rawStationData['tags']))
                    ->map(function ($value) {
                        return is_string($value) ? trim($value) : $value;
                    })->toArray();

                $dbTags = Genre::whereIn('name', $tags)->get();
            }

            $existingStation = RadioStation::where('stationuuid', $rawStationData['stationuuid'])
                ->first();

            if (! $existingStation) {
                $station = $this->createStation($rawStationData, $dbTags->pluck('id')->toArray());

                $dbTags->each(function ($tag) use ($station) {
                    $tag->update([
                        '$addToSet' => ['stations' => new ObjectId($station->id)],
                    ]);
                });

                continue;
            }

            if (! $existingStation->deleted_at) {
                $this->updateStation($existingStation, $rawStationData);
            }
        }

        $this->updateGenreStationCounts();

        $this->log('Completed processing stations.');

        Storage::disk('local')->delete($this->filename);
    }

    private function prepareStationData(array $station)
    {
        $country = Country::where('code', strtolower($station['countrycode']))->first();

        $data = [
            'stationuuid' => $station['stationuuid'],
            'name' => trim($station['name']),
            'url' => trim($station['url']),
            'url_resolved' => trim($station['url_resolved']),
            'homepage' => trim($station['homepage']),
            'favicon' => trim($station['favicon']),
            'country_id' => new ObjectId($country?->id),
            'country' => $station['country'],
            'countrycode' => strtolower($station['countrycode']),
            'state' => $station['state'],
            'language' => ! empty($station['language']) ? explode(',', trim($station['language'])) : [],
            'languagecodes' => ! empty($station['languagecodes']) ? explode(',', trim($station['languagecodes'])) : [],
            'geo_lat' => $station['geo_lat'],
            'geo_long' => $station['geo_long'],
            'location' => ($station['geo_lat'] && $station['geo_long']) ? [
                'type' => 'Point',
                'coordinates' => [$station['geo_long'], $station['geo_lat']],
            ] : null,
            'tags' => $station['tags'],
            'votes' => $station['votes'],
            'codec' => $station['codec'],
            'bitrate' => $station['bitrate'],
            'hls' => $station['hls'] == 1,
            'last_checked_at' => $station['lastchecktime'] ? new \DateTime($station['lastchecktime']) : null,
            'is_working' => $station['lastcheckok'] == 1,
            'ssl_error' => $station['ssl_error'] == 1,
            'clickcount' => $station['clickcount'],
            'has_extended_info' => $station['has_extended_info'] ?? false,
        ];

        return collect($data)->map(function ($value) {
            return empty($value) ? null : $value;
        })->toArray();
    }

    private function updateStation(RadioStation $existingStation, array $rawStationData)
    {
        $stationData = $this->prepareStationData($rawStationData);

        unset(
            $stationData['name'],
            $stationData['last_checked_at'],
            $stationData['is_working'],
            $stationData['stationuuid']
        );

        if (! empty($existingStation['favicon'])) {
            unset($stationData['favicon']); // Do not overwrite existing favicon
        }

        $existingStation->update($stationData);

        $this->log("Station updated: {$rawStationData['stationuuid']}");
    }

    private function createStation(array $station, $genres)
    {
        $slug = RadioStation::generateSlug($station['name']);

        $stationData = $this->prepareStationData($station);

        $stationData['slug'] = $slug;

        $stationData['genres'] = collect($genres)->map(function ($genre) {
            return new ObjectId($genre);
        })->toArray();

        $stationData['created_at'] = now();

        $station = RadioStation::create($stationData);

        $this->log("Station created: {$station['stationuuid']}");

        return $station;
    }

    private function updateGenreStationCounts()
    {
        $genres = Genre::whereRaw([
            'stations' => ['$ne' => [], '$ne' => null],
        ])->get();

        foreach ($genres as $genre) {
            $genre->update(['total_stations' => count($genre->stations)]);
        }
    }
}
