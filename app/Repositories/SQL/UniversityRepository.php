<?php

namespace App\Repositories\SQL;

use App\Models\University;
use App\Repositories\Contracts\UniversityContract;

class UniversityRepository extends BaseRepository implements UniversityContract
{
    /**
     * UniversityRepository constructor.
     * @param University $model
     */
    public function __construct(University $model)
    {
        parent::__construct($model);
    }
}
