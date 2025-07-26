<?php

namespace App\Jobs;

use App\Models\RadioStation;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class DownloadStationImages implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $stationIds;

    const INCOMPATIBLE_IMAGE_FORMATS = ['svg', 'ico'];

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($stationIds)
    {
        $this->onQueue('image-downloader');

        $this->stationIds = $stationIds;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        foreach ($this->stationIds as $stationId) {
            $station = RadioStation::find($stationId);

            Log::info('Processing '.$station->id);
            Log::info('URL '.$station->favicon);

            try {
                $opts = [
                    'http' => [
                        'timeout' => 4,
                    ],
                    'ssl' => [
                        'verify_peer' => false,
                    ],
                ];

                $context = stream_context_create($opts);
                $headers = get_headers($station->favicon, 1, $context);

                if (isset($headers['Content-Type'])) {
                    $contentTypeString = $headers['Content-Type'];

                    if (is_array($contentTypeString)) {
                        $contentTypeString = implode(' ', $contentTypeString);
                    }

                    $isImage = Str::contains($contentTypeString, ['image']);

                    if (! $isImage) {
                        $station->update(['favicon' => null]);
                        Log::error('Not an image '.$station->favicon);
                        continue;
                    }
                }

                $ext = strtolower(last(explode('.', parse_url($station->favicon, PHP_URL_PATH))));

                $path = $this->getStoragePath($station);

                if (in_array($ext, self::INCOMPATIBLE_IMAGE_FORMATS)) {
                    $this->saveIncompatibleImage($station->favicon, $path);
                } else {
                    $this->saveCompatibleImage($station->favicon, $path);
                }

                $station->update(['favicon' => $path, 'has_uploaded_favicon' => true]);

                Log::info('Saved image for '.$station->id);
            } catch (\Exception $e) {
                $station->update(['favicon' => null, 'has_uploaded_favicon' => false]);

                Log::error($e->getMessage());
            }
        }
    }

    private function getStoragePath($station)
    {
        $ext = strtolower(last(explode('.', parse_url($station->favicon, PHP_URL_PATH))));

        if (! in_array($ext, self::INCOMPATIBLE_IMAGE_FORMATS)) {
            $ext = 'webp';
        }

        return 'stations/'.Str::slug($station->name, '-', 'unknown').'.'.$ext;
    }

    private function saveIncompatibleImage(string $url, string $path)
    {
        $opts = [
            'http' => [
                'timeout' => 4,
            ],
            'ssl' => [
                'verify_peer' => false,
            ],
        ];

        $context = stream_context_create($opts);

        try {
            Storage::put($path, file_get_contents($url, false, $context), 'public');

            return true;
        } catch (\Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    private function saveCompatibleImage(string $url, string $path)
    {
        try {
            $imgCanvas = Image::canvas(200, 200);

            $img = Image::make($url)->resize(200, null, function ($constraint) {
                $constraint->aspectRatio();
            });

            $imgCanvas->insert($img, 'center');

            Storage::put($path, $imgCanvas->stream('webp', 100), 'public');

            return true;
        } catch (\Exception $e) {
            throw new Exception($e->getMessage());
        }
    }
}
