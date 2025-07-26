<?php

namespace App\Notifications;

use App\Models\RadioStation;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewStationAdded extends Notification
{
    use Queueable;

    public $station;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(RadioStation $station)
    {
        $this->station = $station;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->line('The introduction to the notification.')
            ->action('Notification Action', url('/'))
            ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'station_id' => $this->station->id,
            'station_name' => $this->station->name,
            'station_slug' => $this->station->slug,
            'image' => $this->station->favicon,
        ];
    }
}
