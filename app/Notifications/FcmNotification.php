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
        // $this->data = (array)$data + ['sound' => 'default'];
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

    public function toFcm($notifiable): FcmMessage
    {
        $title = $this->data['title'];
        $body = $this->data['body'];
        $data = $this->data['data'] ?? [];

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
                        'sound' => 'default', // Add sound if you want
                    ],
                    'fcm_options' => [
                        'analytics_label' => 'analytics',
                    ],
                ],
                'apns' => [
                    'headers' => [
                        'apns-priority' => '10',
                    ],
                    'payload' => [
                        'aps' => [
                            'sound' => 'default', // Add sound if you want,
                        ],
                    ],
                    'fcm_options' => [
                        'analytics_label' => 'analytics',
                    ],
                ],
            ]);
            // ->custom([
            //     'android' => [
            //         'notification' => [
            //             'color' => '#0A0A0A',
            //         ],
            //         'fcm_options' => [
            //             'analytics_label' => 'analytics',
            //         ],
            //     ],
            //     'apns' => [
            //         'fcm_options' => [
            //             'analytics_label' => 'analytics',
            //         ],
            //     ],
            // ]);
    }

}
