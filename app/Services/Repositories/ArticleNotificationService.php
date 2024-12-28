<?php

namespace App\Services\Repositories;

use App\Constants\NotificationTypeConstants;
use App\Models\Article;
use App\Repositories\Contracts\NotificationContract;

class ArticleNotificationService
{
    private NotificationContract $notificationContract;
    private array $notifiedUsers = [];
    private array $notificationData = [];

    public function __construct(NotificationContract $notificationContract)
    {
        $this->notificationContract = $notificationContract;
        $this->notificationData = [
            'title' => 'messages.notification_messages.article.%s.title',
            'body' => 'messages.notification_messages.article.%s.body',
            'type' => '',
            'redirect_type' => 'Article',
            'redirect_id' => '',
            'users' => $this->notifiedUsers
        ];
    }

    public function newArticle(Article $article): void
    {
        if ($article->author?->id) {
            $this->notifiedUsers = [$article->author->id];
            $this->doctorNotify($article, 'new');
        }
    }

    private function doctorNotify($article, $message): void
    {
        $this->notificationData['type'] = NotificationTypeConstants::DOCTOR->value;
        $this->userNotify($article, $message);
    }

    private function userNotify($article, $message, $data = []): void
    {
        if (count($this->notifiedUsers) == 0) return;

        $this->notificationData['title']       = __(sprintf($this->notificationData['title'], $message));
        $this->notificationData['body']        = __(sprintf($this->notificationData['body'], $message));
        $this->notificationData['redirect_id'] = $article->id;
        $this->notificationData['users']       = $this->notifiedUsers;
        $this->notificationData['data']        = $data;

        $this->notificationContract->create($this->notificationData);
    }
}
