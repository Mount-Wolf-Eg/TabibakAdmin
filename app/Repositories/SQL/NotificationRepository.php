<?php

namespace App\Repositories\SQL;

use App\Models\Notification;
use App\Notifications\FcmNotification;
use App\Repositories\Contracts\NotificationContract;

class NotificationRepository extends BaseRepository implements NotificationContract
{
    public function __construct(Notification $model)
    {
        parent::__construct($model);
    }

    public function afterCreate($model, $attributes)
    {
        foreach ($model->users as $user) {
            $user->notify(new FcmNotification($model));
        }
        return $model;
    }

    public function syncRelations($model, $relations): void
    {
        if (!empty($relations['users'])) {
            $model->users()->sync($relations['users']);
        }
    }
}
