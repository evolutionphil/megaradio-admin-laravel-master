<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ads;
use Illuminate\Http\Request;
use Str;

class AdsController extends Controller
{
    public function index()
    {
        return view('pages.admin.ads.index');
    }

    public function create()
    {
        return view('pages.admin.ads.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:ads,name',
            'position' => 'required',
            'url' => 'required',
        ]);

        $ads = Ads::create([
            'name' => $request->name,
            'url' => $request->url,
            'position' => $request->position,
        ]);

        if ($request->image_url) {
            $ads->update([
                'image' => $request->image_url,
            ]);
        } elseif (! $request->image_url && $request->has('image')) {
            $this->updateImage($ads, $request->image);
        }

        return redirect()->route('admin.ads.index')->with('success', 'Ads created successfully.');
    }

    public function edit(Ads $ads)
    {
        return view('pages.admin.ads.edit', compact('ads'));
    }

    private function updateImage($ads, $file)
    {
        if ($ads->image && Storage::disk('public')->exists($ads->image)) {
            Storage::disk('public')->delete($ads->image);
        }

        $image = $file->store('ads/'.Str::random(5), 'public');

        $ads->update([
            'image' => $image,
        ]);
    }

    public function update(Request $request, Ads $ads)
    {
        $ads->update([
            'name' => $request->name,
            'is_discoverable' => isset($request->is_discoverable) && $request->is_discoverable == 'true' ?? false,
            'url' => $request->url,
        ]);

        if ($request->image_url) {
            $ads->update([
                'image' => $request->image_url,
            ]);
        } elseif (! $request->image_url && $request->has('image')) {
            $this->updateImage($ads, $request->image);
        }

        return redirect()->route('admin.ads.index')->with('success', 'Ads updated successfully.');
    }

    public function destroy(Ads $ads)
    {
        $ads->delete();

        return redirect()->route('admin.ads.index')->with('success', 'Ads deleted successfully.');
    }
}
