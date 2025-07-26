<?php

namespace App\Services;

use App\Models\RadioStation;
use Kreait\Firebase\Contract\Messaging;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use Kreait\Firebase\Messaging\WebPushConfig;

class FcmService
{
    const TOPIC_NEW_STATION = 'new_stations';

    public function __construct(Messaging $messaging)
    {
        $this->messaging = $messaging;
    }

    public function notifyStationAdded(RadioStation $radioStation)
    {
        $config = WebPushConfig::fromArray([
            'notification' => [
                'title' => 'New station '.$radioStation->name.' was added.',
                'body' => 'Click here to play the station.',
            ],
            'fcm_options' => [
                'link' => sprintf('https://megaradio.live/radios/%s/%s', $radioStation->slug, $radioStation->id),
            ],
        ]);

        $notification = Notification::create('New station '.$radioStation->name.' was added.');

        $message = CloudMessage::new()
            ->toTopic(self::TOPIC_NEW_STATION)
            ->withWebPushConfig($config)
            ->withNotification($notification);

        $this->messaging->send($message);
    }
}
