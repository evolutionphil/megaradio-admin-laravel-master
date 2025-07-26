<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Genre;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class GenreController extends Controller
{
    public function index()
    {
        return view('pages.admin.genres.index');
    }

    public function create()
    {
        return view('pages.admin.genres.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'unique:genres,name',
        ]);

        $genre = Genre::create([
            'name' => $request->name,
            'slug' => Genre::generateSlug($request->name),
            'is_discoverable' => isset($request->is_discoverable) && $request->is_discoverable == 'true' ?? false,
            'discoverable_label' => $request->discoverable_label,
            'stations' => [],
            'total_stations' => 0,
        ]);

        if ($request->image_url) {
            $genre->update([
                'image' => $request->image_url,
            ]);
        } elseif (! $request->image_url && $request->has('image')) {
            $this->storeGenreImage($genre, $request->image);
        }

        return redirect()->route('admin.genres.edit', $genre)->with('success', 'Genre created successfully.');
    }

    public function edit(Genre $genre)
    {
        return view('pages.admin.genres.edit', compact('genre'));
    }

    private function storeGenreImage($genre, string | UploadedFile $source)
    {
        try {
            if (is_string($source)) {
                $source = file_get_contents($source);
            }

            $path = Storage::put('genres', $source, 'public');

            if ($genre->image && Storage::exists($genre->image)) {
                Storage::delete($genre->image);
            }

            $genre->update([
                'image' => $path,
            ]);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function update(Request $request, Genre $genre)
    {
        $genre->update([
            'name' => $request->name,
            'is_discoverable' => isset($request->is_discoverable) && $request->is_discoverable == 'true' ?? false,
            'discoverable_label' => $request->discoverable_label,
        ]);

        $imageResource = null;

        if ($request->image_url) {
            $imageResource = $request->image_url;
        }

        if (! $request->image_url && $request->has('image')) {
            $imageResource = $request->image;
        }

        if ($imageResource) {
            $this->storeGenreImage($genre, $imageResource);
        }

        return redirect()->back()->with('success', 'Genre updated successfully.');
    }

    public function destroy(Genre $genre)
    {
        $genre->delete();

        return redirect()->route('admin.genres.index')->with('success', 'Genre deleted successfully.');
    }

    public function genrateSitemap()
    {
        dispatch(function () {
        });

        return 'dispatched';
    }
}
