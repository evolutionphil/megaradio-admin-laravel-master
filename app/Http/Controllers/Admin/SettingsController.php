<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function group()
    {
        return view('pages.admin.settings.group');
    }

    public function show()
    {
        $settings = Setting::all();

        $settings = $settings->flatMap(function ($setting) {
            return [$setting->name => $setting->value];
        })->toArray();

        return view('pages.admin.settings.show', compact('settings'));
    }

    public function update(Request $request)
    {
        $settings = $request->except('_token');

        foreach ($settings as $key => $value) {
            Setting::where('name', $key)
                ->update([
                    'value' => $value,
                ]);
        }

        return redirect()->back()->with('success', 'Saved.');
    }
}
