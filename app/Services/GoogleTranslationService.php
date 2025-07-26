<?php

namespace App\Services;

use Google\Cloud\Core\Exception\GoogleException;
use Google\Cloud\Core\Exception\ServiceException;
use Google\Cloud\Translate\V2\TranslateClient;

class GoogleTranslationService
{
    private TranslateClient $client;

    /**
     * @throws GoogleException
     */
    public function __construct()
    {
        $this->client = new TranslateClient([
            'key' => config('services.google_translate.api_key'),
        ]);
    }

    /**
     * @throws ServiceException
     */
    public function translate(array $texts, string $sourceLanguage, string $targetLanguage): ?array
    {
        return $this->client->translateBatch($texts, [
            'source' => $sourceLanguage,
            'target' => $targetLanguage,
            'format' => 'text',
        ]);
    }
}
