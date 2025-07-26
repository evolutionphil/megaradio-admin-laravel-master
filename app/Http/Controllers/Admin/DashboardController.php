<?php

namespace App\Http\Controllers\Admin;

use App\Charts\DailyVisitorsChart;
use App\Http\Controllers\Controller;
use App\Models\Analytics;
use App\Models\RadioStation;
use Illuminate\Support\Facades\Cache;
use MongoDB\BSON\UTCDateTime;

class DashboardController extends Controller
{
    const CACHE_DURATION_IN_SECONDS = 120;

    public function index()
    {
        $daily_visitors = $this->lastWeeksVisitors();

        $daily_visitors_chart = new DailyVisitorsChart;
        $daily_visitors_chart->labels($daily_visitors->keys());
        $daily_visitors_chart->dataset('Daily Visitors', 'line', $daily_visitors->values());

        $total_stations = RadioStation::withoutTrashed()->count();

        $total_visitors = Cache::remember('total_visitors', self::CACHE_DURATION_IN_SECONDS, function () {
            return Analytics::where('type', 'SITE_VISIT')->count();
        });

        $total_shares = Cache::remember('total_shares', self::CACHE_DURATION_IN_SECONDS, function () {
            return Analytics::whereIn('type', ['FACEBOOK_SHARE', 'WHATSAPP_SHARE', 'TWITTER_SHARE'])->count();
        });

        $top_visited_stations = Cache::remember('top_visited_stations', self::CACHE_DURATION_IN_SECONDS, function () {
            return Analytics::raw(function ($collection) {
                return $collection->aggregate([
                    [
                        '$match' => [
                            'type' => 'STATION_VISIT',
                        ],
                    ],
                    [
                        '$group' => [
                            '_id' => '$data.station_id',
                            'total_visits' => [
                                '$sum' => 1,
                            ],
                        ],
                    ],
                    [
                        '$sort' => [
                            'total_visits' => -1,
                        ],
                    ],
                    [
                        '$limit' => 10,
                    ],
                ]);
            });
        });

        return view('dashboard', compact('total_visitors', 'top_visited_stations', 'total_shares', 'total_stations', 'daily_visitors_chart'));
    }

    private function lastWeeksVisitors()
    {
        return Analytics::raw(function ($collection) {
            return $collection->aggregate([
                [
                    '$match' => [
                        'created_at' => [
                            '$gte' => new UTCDateTime((new \DateTime())->modify('-7 days')->getTimestamp() * 1000),
                        ],
                        'type' => 'SITE_VISIT',
                    ],
                ],
                [
                    '$group' => [
                        '_id' => [
                            'day' => [
                                '$dateToString' => [
                                    'format' => '%Y-%m-%d',
                                    'date' => '$created_at',
                                ],
                            ],
                            'session_id' => '$session_id',
                        ],
                        'count' => ['$sum' => 1],
                    ],
                ],
                [
                    '$group' => [
                        '_id' => '$_id.day',
                        'session_count' => ['$sum' => 1],
                    ],
                ],
                [
                    '$sort' => [
                        '_id' => 1,
                    ],
                ],
            ]);
        })->flatMap(fn ($item) => [$item['id'] => $item['session_count']]);
    }
}
