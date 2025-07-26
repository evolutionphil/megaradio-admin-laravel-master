<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class ParsedUrlToStringTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_parse_a_base_url()
    {
        $this->assertEquals('https://14083.live.streamtheworld.com:443/KSKYAMAAC.aac', parsedUrlToString(parse_url('https://14083.live.streamtheworld.com:443/KSKYAMAAC.aac')));
        $this->assertEquals('http://radios.topymedia.com:8054/stream/;.mp3', parsedUrlToString(parse_url('http://radios.topymedia.com:8054/stream/;.mp3')));
        $this->assertEquals('https://google.com', parsedUrlToString(parse_url('https://google.com')));
        $this->assertEquals('https://rautemusik-de-hz-fal-stream14.radiohost.de/techhouse?ref=radiobrowser&_art=dD0xNzMxMzMzMTAzJmQ9ZWVjMDAzZWY1OGFhZTQxOWQ4ZGM', parsedUrlToString(parse_url('https://rautemusik-de-hz-fal-stream14.radiohost.de/techhouse?ref=radiobrowser&_art=dD0xNzMxMzMzMTAzJmQ9ZWVjMDAzZWY1OGFhZTQxOWQ4ZGM')));
    }

    public function testBasicUrl()
    {
        $expected = 'http://example.com';
        $this->assertEquals($expected, parsedUrlToString(parse_url($expected)));
    }

    public function testCompleteUrl()
    {
        $expected = 'https://username:password@example.com:8080/path/to/page?param1=value1&param2=value2#section1';
        $this->assertEquals($expected, parsedUrlToString(parse_url($expected)));
    }

    public function testUrlWithoutScheme()
    {
        $expected = 'example.com/path';
        $this->assertEquals($expected, parsedUrlToString(parse_url($expected)));
    }

    public function testUrlWithQueryString()
    {
        $expected = 'https://example.com?param1=value1&param2=value2';
        $this->assertEquals($expected, parsedUrlToString(parse_url($expected)));
    }

    public function testUrlWithFragment()
    {
        $expected = 'https://example.com#section1';
        $this->assertEquals($expected, parsedUrlToString(parse_url($expected)));
    }

    public function testUrlWithAuthentication()
    {
        $expected = 'https://username:password@example.com';
        $this->assertEquals($expected, parsedUrlToString(parse_url($expected)));
    }

    public function testUrlWithPort()
    {
        $expected = 'https://example.com:8080';
        $this->assertEquals($expected, parsedUrlToString(parse_url($expected)));
    }

    public function testUrlWithOnlyUsername()
    {
        $expected = 'https://username@example.com';
        $this->assertEquals($expected, parsedUrlToString(parse_url($expected)));
    }

    public function testEmptyArray()
    {
        $this->assertEquals('', parsedUrlToString([]));
    }

    public function testSpecialCharactersInQueryString()
    {
        $expected = 'https://example.com?name=John+Doe&email=john%40example.com';
        $this->assertEquals($expected, parsedUrlToString(parse_url($expected)));
    }

    public function testInternationalDomainName()
    {
        $expected = 'https://üexample.com/über';
        $this->assertEquals($expected, parsedUrlToString(parse_url($expected)));
    }
}
