<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\NotifyNewStationAdditionJob;
use App\Mail\StationSubmissionApproved;
use App\Models\Country;
use App\Models\Genre;
use App\Models\RadioStation;
use App\Models\StationSubmission;
use Mail;
use MongoDB\BSON\ObjectID;
use Str;

class StationSubmissionController extends Controller
{
    public function index()
    {
        return view('pages.admin.station-submissions.index');
    }

    public function approve(StationSubmission $stationSubmission)
    {
        try {
            $country = Country::where('name', $stationSubmission->country)->first();
            $genre = Genre::where('name', trim($stationSubmission->genre))->first();

            $radioStation = RadioStation::create([
                'name' => $stationSubmission->name,
                'slug' => RadioStation::generateSlug($stationSubmission->name),
                'url' => $stationSubmission->stream_url,
                'url_resolved' => $stationSubmission->stream_url,
                'homepage' => $stationSubmission->website,
                'favicon' => $stationSubmission->logo,
                'genres' => ($genre) ? [new ObjectID($genre->id)] : [],
                'tags' => $stationSubmission->genre,
                'country' => $stationSubmission->country,
                'countrycode' => optional($country)->code,
                'state' => $stationSubmission->state,
                'language' => [],
                'languagecodes' => [],
                'geo_lat' => null,
                'geo_long' => null,
                'location' => null,
                'codec' => null,
                'bitrate' => null,
                'hls' => 0,
                'votes' => 0,
                'clickcount' => 0,
                'is_working' => true,
                'featured' => false,
                'popular' => false,
            ]);

            if ($genre) {
                $stations = array_merge($genre->stations ?: [], [new ObjectID($radioStation->id)]);
                $genre->update([
                    'stations' => $stations,
                    'total_stations' => count($stations),
                ]);
            }

            $stationSubmission->update(['status' => 'approved']);

            $radioUrl = 'https://megaradio.live/en/radios/'.Str::slug($radioStation->name, '-', 'unknown').'/'.$radioStation->id;

            if ($stationSubmission->email && filter_var($stationSubmission->email, FILTER_VALIDATE_EMAIL)) {
                Mail::to($stationSubmission->email)
                    ->queue(new StationSubmissionApproved($radioUrl));
            }

            NotifyNewStationAdditionJob::dispatch($radioStation)->delay(10);

            return redirect()->back()->with('success', 'Approved.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function destroy(StationSubmission $stationSubmission)
    {
        $stationSubmission->destroy();

        return redirect()->back()->with('success', 'Deleted.');
    }
}
