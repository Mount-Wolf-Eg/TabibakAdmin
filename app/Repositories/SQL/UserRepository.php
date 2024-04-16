<?php

namespace App\Repositories\SQL;

use App\Constants\FileConstants;
use App\Models\User;
use App\Repositories\Contracts\FileContract;
use App\Repositories\Contracts\RoleContract;
use App\Repositories\Contracts\UserContract;

class UserRepository extends BaseRepository implements UserContract
{
    /**
     * UserRepository constructor.
     * @param User $model
     */
    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    public function syncRelations($model, $attributes)
    {
        if (isset($attributes['role_id'])) {
            $role = resolve(RoleContract::class)->find($attributes['role_id']);
            $model->syncRoles($role->name);
        }
        if (isset($attributes['image'])) {
            if ($model->avatar)
                resolve(FileContract::class)->remove($model->avatar);
            if (is_numeric($attributes['image'])) {
                $file = resolve(FileContract::class)->find($attributes['image']);
            }else{
                $file = resolve(FileContract::class)->create(['file' => $attributes['image'],
                    'type' => FileConstants::FILE_TYPE_USER_AVATAR->value]);
            }
            $model->avatar()->save($file);
        }
        return $model;
    }

    public function prepareUserForRoleUsers($attributes): array
    {
        if (isset($attributes['user'])) {
            if (isset($attributes['user']['id'])) {
                $user = $this->find($attributes['user']['id']);
                unset($attributes['user']['id']);
                $user->update($attributes['user']);
            } else {
                $user = $this->create($attributes['user']);
            }
            unset($attributes['user']);
            $attributes['user_id'] = $user->id;
        }
        return $attributes;
    }
}
