<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Genre;
use Illuminate\Http\Request;

class GenreController extends Controller
{
    public function index(Request $request)
    {
        $query = Genre::query();

        if ($request->has('searchQuery')) {
            $query->where('name', 'like', '%'.$request->searchQuery.'%');
        }

        if ($request->has('ids')) {
            $query->whereIn('id', explode(',', $request->get('ids')));
        }

        return $query->select(['name', 'total_stations', 'discoverable_label', 'is_discoverable', 'image'])->paginate(10);
    }
}
