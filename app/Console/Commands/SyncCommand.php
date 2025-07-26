<?php

namespace App\Console\Commands;

use App\Jobs\DownloadStation;
use App\Models\Country;
use App\Models\Genre;
use App\Models\Setting;
use App\Models\State;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use MongoDB\BSON\ObjectId;

class SyncCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:sync {--station-limit=500}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Syncs database with radio-browser api';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->setLastSyncStartedAt();

        $this->info('Sync countries.');
        $this->syncCountries();

        $this->info('Sync states.');
        $this->syncStates();

        $this->info('Sync genres.');
        $this->syncGenres();

        $totalWorkingStations = $this->getTotalStationCount();

        $stationLimit = $this->option('station-limit');

        for ($i = 0; $i <= $totalWorkingStations / $stationLimit; $i++) {
            DownloadStation::dispatch($i, $stationLimit);
        }
    }

    private function setLastSyncStartedAt(): void
    {
        Setting::updateOrCreate(
            ['name' => 'radio-browser::last_sync_started_at'],
            ['value' => now()->toIso8601String()]
        );
    }

    private function getTotalStationCount()
    {
        $response = Http::get('http://all.api.radio-browser.info/json/stats');

        return $response->json('stations') - $response->json('stations_broken');
    }

    public function syncCountries(): void
    {
        $response = Http::get('http://all.api.radio-browser.info/json/countries', [
            'order' => 'name',
        ]);

        foreach ($response->json() as $country) {
            $countryName = trim($country['name']);

            if (empty($countryName) || empty($country['iso_3166_1'])) {
                continue;
            }

            Country::firstOrCreate([
                'code' => strtolower(trim($country['iso_3166_1'])),
            ], [
                'name' => $countryName,
            ]);
        }
    }

    public function syncStates(): void
    {
        $response = Http::get('http://all.api.radio-browser.info/json/states', [
            'order' => 'name',
        ]);

        foreach ($response->json() as $state) {
            $countryName = trim($state['country']);
            $stateName = trim($state['name']);

            $country = Country::where('name', $countryName)->first();

            if ($country) {
                State::firstOrCreate([
                    'name' => $stateName,
                    'country' => new ObjectId($country->id),
                ]);
            }
        }
    }

    private function getGenres(): array
    {
        $response = Http::get('http://all.api.radio-browser.info/json/tags', [
            'order' => 'stationcount',
            'reverse' => true,
        ]);

        return $response->json();
    }

    public function syncGenres(): void
    {
        $genres = $this->getGenres();

        foreach ($genres as $genre) {
            $name = trim($genre['name']);

            if (! isset($name) || in_array($name, ['null', '', 'undefined'])) {
                continue;
            }

            Genre::firstOrCreate([
                'name' => $name,
            ], [
                'slug' => Genre::generateSlug($name),
            ]);
        }
    }
}
