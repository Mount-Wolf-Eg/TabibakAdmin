<?php

namespace App\Repositories\SQL;

use App\Models\Hospital;
use App\Repositories\Contracts\HospitalContract;

class HospitalRepository extends BaseRepository implements HospitalContract
{
    /**
     * HospitalRepository constructor.
     * @param Hospital $model
     */
    public function __construct(Hospital $model)
    {
        parent::__construct($model);
    }
}
