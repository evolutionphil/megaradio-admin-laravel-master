<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StationRequest;

class StationRequestController extends Controller
{
    public function index()
    {
        auth()->user()->unreadNotifications->markAsRead();

        return view('pages.admin.station-requests.index');
    }

    public function show(StationRequest $id)
    {
        //
    }

    public function destroy(StationRequest $stationRequest)
    {
        $stationRequest->destroy();

        return redirect()->back()->with('success', 'Deleted.');
    }
}
