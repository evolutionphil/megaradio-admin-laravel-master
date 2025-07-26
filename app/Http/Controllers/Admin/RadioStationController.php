<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\RadioStationRequest;
use App\Jobs\NotifyNewStationAdditionJob;
use App\Models\Country;
use App\Models\Genre;
use App\Models\Language;
use App\Models\RadioStation;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;
use MongoDB\BSON\ObjectId;
use Storage;

class RadioStationController extends Controller
{
    private function storeImage($station, string | UploadedFile $source)
    {
        if (is_string($source)) {
            $source = file_get_contents($source);
        }

        $path = Storage::put('stations', $source, 'public');

        if ($station->favicon && Storage::exists($station->favicon)) {
            Storage::delete($station->favicon);
        }

        $station->update([
            'favicon' => $path,
        ]);
    }

    public function index()
    {
        return view('pages.admin.radio-stations.index');
    }

    public function create(Request $request)
    {
        return view('pages.admin.radio-stations.create');
    }

    public function store(RadioStationRequest $request)
    {
        $data = $request->except(['id', '_token', '_method', 'favicon_file', 'tags']);

        $data['hls'] = isset($request->hls);
        $data['featured'] = isset($request->featured);
        $data['is_working'] = isset($request->is_working);
        $data['is_global'] = isset($request->is_global);
        $data['popular'] = isset($request->popular);

        $data['slug'] = RadioStation::generateSlug($request->get('name'));

        if ($request->has('country')) {
            $country = Country::where('name', $request->get('country'))->first();

            $data['country'] = $country->name;
            $data['countrycode'] = Str::lower($country->code);
        }

        $radioStation = RadioStation::create($data);

        $this->saveTags($radioStation, $request->get('tags', []));

        if ($request->has('favicon_file')) {
            $favicon = $request->favicon_file->store('stations/'.$radioStation->id, 'public');

            $radioStation->update([
                'favicon' => $favicon,
            ]);
        }

        NotifyNewStationAdditionJob::dispatch($radioStation)->delay(10);

        return redirect()->back()->with('success', 'Radio station created successfully.');
    }

    private function saveTags(RadioStation $radioStation, $requestTags = [])
    {
        $genreIds = [];

        foreach ($requestTags as $tag) {
            if (isValidMongoId($tag)) {
                $genreIds[] = $tag;
            } else {
                $genreIds[] = Genre::firstOrCreate(['name' => $tag])->id;
            }
        }

        $tags = Genre::whereIn('id', $genreIds)->select('name')->pluck('name');

        $data = [
            'tags' => $tags->implode(','),
            'genres' => collect($genreIds)->map(function ($id) {
                return new ObjectId($id);
            })->toArray(),
        ];

        foreach ($genreIds as $genreId) {
            $genre = Genre::find($genreId);

            $genre->stations = array_unique(array_merge($genre->stations ?? [], [new ObjectId($radioStation->id)]));
            $genre->total_stations = count($genre->stations);

            $genre->save();
        }

        $radioStation->update($data);
    }

    public function show(RadioStation $radioStation)
    {
        return view('pages.admin.radio-stations.show', compact('radioStation'));
    }

    public function linkedStations(RadioStation $radioStation)
    {
        return view('pages.admin.radio-stations.linked-stations', compact('radioStation'));
    }

    public function edit(RadioStation $radioStation)
    {
        $languages = Language::select(['name', 'key'])->orderBy('name')->get();

        return view('pages.admin.radio-stations.edit', compact('radioStation', 'languages'));
    }

    public function update(RadioStationRequest $request, RadioStation $radioStation)
    {
        try {
            $data = $request->except(['id', '_token', '_method', 'favicon_file', 'tags']);

            if ($request->has('country')) {
                $country = Country::where('name', $request->get('country'))->first();

                $data['country'] = $country->name;
                $data['countrycode'] = Str::lower($country->code);
            }

            $this->saveTags($radioStation, $request->get('tags', []));

            $data['featured'] = isset($request->featured);
            $data['is_working'] = isset($request->is_working);
            $data['is_global'] = isset($request->is_global);
            $data['hls'] = isset($request->hls);
            $data['popular'] = isset($request->popular);
            $data['descriptions'] = $request->get('descriptions', []);

            $radioStation->update($data);

            $favicon = null;

            if ($request->favicon_url) {
                $favicon = $request->favicon_url;
            }

            if (! $request->favicon_url && $request->has('favicon_file')) {
                $favicon = $request->favicon_file;
            }

            if ($favicon) {
                $this->storeImage($radioStation, $favicon);
            }

            return redirect()->back()->with('success', 'Radio station updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function destroy(RadioStation $radioStation)
    {
        $radioStation->delete();

        return redirect()->back()->with('success', 'Radio station deleted successfully.');
    }

    public function restore(RadioStation $radioStation)
    {
        $radioStation->restore();

        return redirect()->back()->with('success', 'Radio station restored successfully.');
    }

    public function sync()
    {
        Artisan::queue('app:sync')
            ->onQueue('station-downloader');

        return redirect()->route('admin.radio-stations.index')->with('success', 'Radio stations sync job dispatched.');
    }
}
