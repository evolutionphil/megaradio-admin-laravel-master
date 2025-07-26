<?php

namespace App\Services;

use App\Models\Genre;
use App\Models\RadioStation;
use Illuminate\Support\Facades\Storage;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\SitemapIndex;
use Spatie\Sitemap\Tags\Url;

class SitemapService
{
    const PAGE_SIZE = 1000;

    const BASE_URL = 'https://megaradio.live';

    public function generateGenreSitemap($lang = 'en')
    {
        if (! Storage::disk('public')->exists('sitemaps/'.$lang.'/genres')) {
            Storage::disk('public')->makeDirectory('sitemaps/'.$lang.'/genres');
        }

        $sitemapIndex = SitemapIndex::create();

        Genre::raw(function ($collection) {
            return $collection->aggregate([
                ['$lookup' => [
                    'from' => 'radio_stations', // Make sure this matches your stations collection name
                    'localField' => 'stations',
                    'foreignField' => '_id',
                    'as' => 'station_details',
                ]],

                // Filter out genres with no stations
                ['$match' => [
                    'station_details' => ['$ne' => []],
                    'station_details.is_working' => true,
                    'station_details.deleted_at' => null,
                ]],

                // Unwind the stations array
                ['$unwind' => '$station_details'],

                // Group by genre and count stations
                ['$group' => [
                    '_id' => '$_id',
                    'slug' => ['$first' => '$slug'],
                    'station_count' => ['$sum' => 1],
                ]],

                // Sort by station count in descending order
                ['$sort' => ['station_count' => -1]],
            ]);
        })->chunk(self::PAGE_SIZE)->each(function ($genres, $key) use ($sitemapIndex, $lang) {
            $name = 'sitemaps/'.$lang.'/genres/'.$key.'.xml';

            $sitemap = Sitemap::create();

            foreach ($genres as $genre) {
                $genreUrl = 'https://megaradio.live/'.$lang.'/genres/'.$genre->slug;

                if ($lang == 'en') {
                    $genreUrl = 'https://megaradio.live/genres/'.$genre->slug;
                }

                $url = Url::create($genreUrl)
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY);

                $sitemap->add($url);
            }

            $sitemap->writeToFile(Storage::disk('public')->path($name));

            $sitemapIndex->add('https://megaradio.live/'.$name);

            gc_collect_cycles();
        });

        $sitemapIndex->writeToFile(Storage::disk('public')->path('sitemaps/'.$lang.'/genres/index.xml'));
    }

    public function generateStationsSitemap($lang = 'en')
    {
        if (! Storage::disk('public')->exists('sitemaps/'.$lang.'/stations')) {
            Storage::disk('public')->makeDirectory('sitemaps/'.$lang.'/stations');
        }

        $sitemapIndex = SitemapIndex::create();

        $query = RadioStation::where('is_working', true)
            ->orderBy('votes', 'desc')
            ->orderBy('clickcount', 'desc');

        $query->select(['id', 'slug', 'favicon'])
            ->chunkById(self::PAGE_SIZE, function ($stations, $key) use (&$i, $sitemapIndex, $lang) {
                $name = 'sitemaps/'.$lang.'/stations/'.$key.'.xml';

                $sitemap = Sitemap::create();

                foreach ($stations as $station) {
                    $stationUrl = 'https://megaradio.live/'.$lang.'/'.$station->slug;

                    if ($lang == 'en') {
                        $stationUrl = 'https://megaradio.live/'.$station->slug;
                    }

                    $url = Url::create($stationUrl)
                        ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY);

                    if ($station->hasLogo) {
                        $url->addImage($station->favicon);
                    }

                    $sitemap->add($url);
                }
                $sitemap->writeToFile(Storage::disk('public')->path($name));

                $sitemapIndex->add('https://megaradio.live/'.$name);

                gc_collect_cycles();
            });

        $sitemapIndex->writeToFile(Storage::disk('public')->path('sitemaps/'.$lang.'/stations/index.xml'));
    }

    public function generatePagesSitemap($lang = 'en')
    {
        if (! Storage::disk('public')->exists('sitemaps/'.$lang)) {
            Storage::disk('public')->makeDirectory('sitemaps/'.$lang);
        }

        $sitemap = Sitemap::create();

        $paths = [
            '/about',
            '/contact',
            '/feedback',
            '/pages/privacy-policy',
            '/pages/terms-and-conditions',
        ];

        $sitemap->add(
            Url::create($lang == 'en' ? self::BASE_URL : self::BASE_URL.'/'.$lang)
                ->setPriority(0.1)
        );

        foreach ($paths as $path) {
            $url = self::BASE_URL.'/'.$lang.$path;

            if ($lang == 'en') {
                $url = self::BASE_URL.$path;
            }

            $sitemap->add(
                Url::create($url)
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                    ->setPriority(0.2)
            );
        }

        $sitemap->writeToFile(Storage::disk('public')->path('sitemaps/'.$lang.'/pages.xml'));
    }

    public function generateIndexSitemap($lang)
    {
        if (! Storage::disk('public')->exists('sitemaps/'.$lang.'/stations')) {
            Storage::disk('public')->makeDirectory('sitemaps/'.$lang.'/stations');
        }

        $sitemapIndex = SitemapIndex::create();

        $sitemapIndex->add('https://megaradio.live/sitemaps/'.$lang.'/pages.xml');
        $sitemapIndex->add('https://megaradio.live/sitemaps/'.$lang.'/stations/index.xml');
        $sitemapIndex->add('https://megaradio.live/sitemaps/'.$lang.'/genres/index.xml');

        $sitemapIndex->writeToFile(Storage::disk('public')->path('sitemaps/'.$lang.'/index.xml'));
    }
}
