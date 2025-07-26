<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Language;
use Illuminate\Http\Request;
use Rap2hpoutre\FastExcel\FastExcel;

class DownloadLanguageImportSampleController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|\Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function __invoke(Request $request)
    {
        $phrases = collect(Language::where('key', 'en')->select('phrases')->first()->phrases)
            ->map(fn ($phrase, $key) => ['key' => $key, 'en' => $phrase]);

        return (new FastExcel($phrases))->download('language-import-sample-file.csv');
    }
}
