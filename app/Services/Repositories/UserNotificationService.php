<?php

namespace App\Services\Repositories;

use App\Constants\NotificationTypeConstants;
use App\Repositories\Contracts\NotificationContract;

class UserNotificationService
{
    private NotificationContract $notificationContract;

    private array $notifiedUsers    = [];
    private array $notificationData = [];

    public function __construct(NotificationContract $notificationContract)
    {
        $this->notificationContract = $notificationContract;
        $this->notificationData = [
            'title'         => 'messages.notification_messages.user.%s.title',
            'body'          => 'messages.notification_messages.user.%s.body',
            'type'          => '',
            'redirect_type' => '',
            'redirect_id'   => '',
            'users'         => $this->notifiedUsers
        ];
    }

    public function approveDoctor($user): void
    {
        $this->notifiedUsers = [$user->id];
        $this->doctorNotify('approve');
    }

    private function doctorNotify($message): void
    {
        $this->notificationData['type'] = NotificationTypeConstants::DOCTOR->value;
        $this->userNotify($message);
    }

    private function userNotify($message, $data = []): void
    {
        if (count($this->notifiedUsers) == 0) return;

        $this->notificationData['title'] = __(sprintf($this->notificationData['title'], $message));
        $this->notificationData['body']  = __(sprintf($this->notificationData['body'], $message));
        $this->notificationData['users'] = $this->notifiedUsers;
        $this->notificationData['data']  = $data;

        $this->notificationContract->create($this->notificationData);
    }
}
