<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use JsonException;
use NotificationChannels\Fcm\FcmChannel;
use NotificationChannels\Fcm\FcmMessage;
use NotificationChannels\Fcm\Resources\Notification as FcmNotificationResource;

class FcmNotification extends Notification implements ShouldQueue
{
    use Queueable;

    private mixed $data;

    /**
     * Create a new notification instance.
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via($notifiable): array
    {
        return [FcmChannel::class];
    }

    /**
     * @throws JsonException
     */
    public function toFcm($notifiable): FcmMessage
    {
        $title = $this->data['title'];
        $body = $this->data['body'];
        $data = $this->handlingNotificationData();
        return (new FcmMessage(notification: new FcmNotificationResource(
            title: $title,
            body: $body,
            image: ''
        )))
            ->data($data)
            ->custom([
                'android' => [
                    'notification' => [
                        'color' => '#0A0A0A',
                    ],
                    'fcm_options' => [
                        'analytics_label' => 'analytics',
                    ],
                ],
                'apns' => [
                    'fcm_options' => [
                        'analytics_label' => 'analytics',
                    ],
                ],
            ]);
    }

    /**
     * @throws JsonException
     */
    private function handlingNotificationData(): array
    {
        $data = $this->data;
        $actionsData = isset($data['data']['actions']) ? $this->convertActionDataToJson() : [];
        return $data['data'] ? array_merge($data['data'], $actionsData) : [];
    }

    /**
     * @throws JsonException
     */
    private function convertActionDataToJson(): array
    {
        $actionsData = [];
        $actionsData['actions'] = json_encode(array_map('json_encode', $this->data['data']['actions']), JSON_THROW_ON_ERROR);
        $actionsData['actionsEnabled'] = json_encode($this->data['data']['actionsEnabled'], JSON_THROW_ON_ERROR);
        $actionsData['permission'] = $this->data['data']['permission'];
        return $actionsData;
    }

}
