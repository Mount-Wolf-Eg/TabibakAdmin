<?php

namespace App\Repositories\SQL;

use App\Models\Patient;
use App\Repositories\Contracts\PatientContract;
use App\Repositories\Contracts\UserContract;

class PatientRepository extends BaseRepository implements PatientContract
{
    /**
     * PatientRepository constructor.
     * @param Patient $model
     */
    public function __construct(Patient $model)
    {
        parent::__construct($model);
    }

    public function beforeCreate($attributes)
    {
        return resolve(UserContract::class)->prepareUserForRoleUsers($attributes);
    }

    public function beforeUpdate($attributes)
    {
        return resolve(UserContract::class)->prepareUserForRoleUsers($attributes);
    }

    public function syncRelations($model, $relations): void
    {
        if (isset($relations['diseases'])) {
            $model->diseases()->sync($relations['diseases']);
        }
    }

}
