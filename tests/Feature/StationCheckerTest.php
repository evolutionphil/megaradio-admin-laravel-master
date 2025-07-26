<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class StationCheckerTest extends TestCase
{
    public function test_station_url_is_working_with_valid_audio_stream()
    {
        // Mock a successful response with Content-Type audio/mpeg
        Http::fake([
            'http://radio1.com/stream' => Http::response('', 200, ['Content-Type' => 'audio/mpeg']),
        ]);

        $stationUrl = 'http://radio1.com/stream';
        $this->assertTrue(isStationUrlWorking($stationUrl));
    }

    public function test_station_url_is_working_with_valid_audio_ogg_stream()
    {
        // Mock a successful response with Content-Type audio/ogg
        Http::fake([
            'http://radio2.com/stream' => Http::response('', 200, ['Content-Type' => 'audio/ogg']),
        ]);

        $stationUrl = 'http://radio2.com/stream';
        $this->assertTrue(isStationUrlWorking($stationUrl));
    }

    public function test_station_url_returns_html_page_instead_of_audio_stream()
    {
        // Mock a response with HTML content (could be an error page)
        Http::fake([
            'http://radio3.com/stream' => Http::response('<html><body>Error</body></html>', 404, ['Content-Type' => 'text/html']),
        ]);

        $stationUrl = 'http://radio3.com/stream';
        $this->assertFalse(isStationUrlWorking($stationUrl));
    }

    public function test_station_url_returns_redirect_to_audio_stream()
    {
        // Mock a redirect response (302) with the correct Content-Type for audio
        Http::fake([
            'http://radio4.com/stream' => Http::response('', 302, ['Content-Type' => 'text/html', 'Location' => 'http://radio5.com/stream']),
            'http://radio5.com/stream' => Http::response('', 200, ['Content-Type' => 'audio/mpeg']),
        ]);

        $stationUrl = 'http://radio4.com/stream';
        $this->assertTrue(isStationUrlWorking($stationUrl));
    }

    public function test_station_url_is_not_working_due_to_timeout_or_network_error()
    {
        // Simulate a network error (timeout or any other exception)
        Http::fake([
            'http://radio6.com/stream' => Http::response('', 408),
        ]);

        $stationUrl = 'http://radio6.com/stream';
        $this->assertFalse(isStationUrlWorking($stationUrl));
    }

    public function test_real_station_url_is_working()
    {
        $this->assertTrue(isStationUrlWorking('https://rautemusik-de-hz-fal-stream14.radiohost.de/techhouse?ref=radiobrowser&_art=dD0xNzMxMzMzMTAzJmQ9ZWVjMDAzZWY1OGFhZTQxOWQ4ZGM'));
    }

    public function test_playlist_url_is_working()
    {
        $this->assertTrue(isStationUrlWorking('http://1192747t.ha.azioncdn.net/primary/ita_poa.sdp/playlist.m3u8'));
    }
}
