<?php

namespace App\Http\Controllers;

use App\Jobs\CheckStationWorkingStatusJob;
use App\Jobs\DownloadStationImages;
use Arr;
use Illuminate\Http\Request;

class JobController extends Controller
{
    const JOBS = [
        'download_station_images' => DownloadStationImages::class,
        'check_station_working_status' => CheckStationWorkingStatusJob::class,
    ];

    public function dispatchJob(Request $request)
    {
        if (! Arr::exists(self::JOBS, $request->job)) {
            return abort(404);
        }

        $job = self::JOBS[$request->job];

        $job::dispatch(...$request->get('args', []));
    }
}
