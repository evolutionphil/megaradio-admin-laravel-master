<?php

namespace App\Http\Controllers;

use App\Models\RadioStation;
use Illuminate\Http\Request;

class TestController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $stations = RadioStation::query()
            ->select(['name', 'is_working', 'tags', 'country', 'favicon'])
            ->orderBy('name', 'asc')
            ->limit(10)
            ->get();

        return $stations;
    }
}
