<?php

namespace App\Services;

use GuzzleHttp\Client;

class RadioBrowserApiService
{
    private Client $client;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => 'http://all.api.radio-browser.info',
        ]);
    }

    public function create(array $attributes)
    {
        $response = $this->client->request('POST', 'json/add', [
            'json' => $attributes,
        ]);

        return $response->getStatusCode() === 201;
    }
}
