<?php

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use MongoDB\BSON\ObjectID;

const VALID_AUDIO_FORMATS = [
    'audio/3gpp2',
    'audio/aac',
    'audio/aacp',
    'audio/adpcm',
    'audio/aiff',
    'audio/x-aiff',
    'audio/basic',
    'audio/flac',
    'audio/midi',
    'audio/mp4',
    'audio/mp4a-latm',
    'audio/mpeg',
    'audio/ogg',
    'audio/opus',
    'audio/vnd.digital-winds',
    'audio/vnd.dts',
    'audio/vnd.dts.hd',
    'audio/vnd.lucent.voice',
    'audio/vnd.ms-playready.media.pya',
    'audio/vnd.nuera.ecelp4800',
    'audio/vnd.nuera.ecelp7470',
    'audio/vnd.nuera.ecelp9600',
    'audio/vnd.wav',
    'audio/wav',
    'audio/x-wav',
    'audio/vnd.wave',
    'audio/wave',
    'audio/x-pn-wav',
    'audio/webm',
    'audio/x-matroska',
    'audio/x-mpegurl',
    'audio/x-ms-wax',
    'audio/x-ms-wma',
    'audio/x-pn-realaudio',
    'audio/x-pn-realaudio-plugin',
    'audio/x-mpeg',
    'audio/mp3',
    'audio/x-mp3',
    'audio/mpeg3',
    'audio/x-mpeg3',
    'application/vnd.apple.mpegurl',
    'audio/x-scpls',
    'application/x-mpegurl',
];

if (! function_exists('isValidMongoId')) {
    function isValidMongoId($value): bool
    {
        if ($value instanceof ObjectID) {
            return true;
        }
        try {
            new ObjectID($value);

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}

if (! function_exists('isAcceptableAudioFormat')) {
    function isAcceptableAudioFormat(string $contentType): bool
    {
        $contentType = Str::lower($contentType);

        if (Str::contains($contentType, VALID_AUDIO_FORMATS)) {
            return true;
        }

        return false;
    }
}

if (! function_exists('isStationUrlWorking')) {
    function isStationUrlWorking(string $url): bool
    {
        try {
            $context = stream_context_create([
                'http' => [
                    'timeout' => 3,
                ],
                'ssl' => [
                    'verify_peer' => Str::startsWith($url, 'https'),
                ],
            ]);

            $headers = get_headers($url, 1, $context);

            $statusCode = (int) substr($headers[0], 9, 3);

            if ($statusCode === 301 || $statusCode === 302) {
                $url = $headers['Location'];

                $headers = get_headers($url, 1, $context);

                $statusCode = (int) substr($headers[0], 9, 3);
            }

            if ($statusCode !== 200) {
                return false;
            }

            $contentType = $headers['Content-Type'] ?? '';

            if (empty($contentType)) {
                return false;
            }

            if (is_array($contentType)) {
                foreach ($contentType as $type) {
                    $type = Str::lower($type);

                    $type = explode(';', $type)[0];

                    if (isAcceptableAudioFormat($type)) {
                        return true;
                    }
                }
            } else {
                $type = explode(';', $contentType)[0];

                return isAcceptableAudioFormat($type);
            }

            return false;
        } catch (Exception $exception) {
            echo $exception->getMessage().PHP_EOL;

            return false;
        }
    }
}

if (! function_exists('parsedUrlToString')) {
    function parsedUrlToString($parsed_url): string
    {
        $scheme = isset($parsed_url['scheme']) ? $parsed_url['scheme'].'://' : '';

        // Handle username and password if present
        $auth = '';
        if (isset($parsed_url['user'])) {
            $auth = $parsed_url['user'];
            if (isset($parsed_url['pass'])) {
                $auth .= ':'.$parsed_url['pass'];
            }
            $auth .= '@';
        }

        // Handle host
        $host = $parsed_url['host'] ?? '';

        // Handle port
        $port = isset($parsed_url['port']) ? ':'.$parsed_url['port'] : '';

        // Handle path
        $path = $parsed_url['path'] ?? '';

        // Handle query string
        $query = isset($parsed_url['query']) ? '?'.$parsed_url['query'] : '';

        // Handle fragment/anchor
        $fragment = isset($parsed_url['fragment']) ? '#'.$parsed_url['fragment'] : '';

        // Combine all components
        return $scheme.$auth.$host.$port.$path.$query.$fragment;
    }
}

if (! function_exists('getImageUrl')) {
    function getImageUrl($path)
    {
        if (str_contains($path, 'http')) {
            return $path;
        }

        return Storage::url($path);
    }
}

if (! function_exists('getPageMetaValue')) {
    function getPageMetaValue($meta, $page, $key)
    {
        if (! $meta) {
            return '';
        }

        if (! isset($meta[$page])) {
            return '';
        }

        if (! isset($meta[$page][$key])) {
            return '';
        }

        return $meta[$page][$key];
    }
}
