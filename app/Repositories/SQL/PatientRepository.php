<?php

namespace App\Repositories\SQL;

use App\Models\Patient;
use App\Repositories\Contracts\PatientContract;
use App\Repositories\Contracts\UserContract;
use Illuminate\Database\Eloquent\Model;

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

    public function remove(Model $model): mixed
    {
        $model->update(['national_id' => null, 'old_national_id' => $model->national_id]);
        return parent::remove($model);
    }
}
