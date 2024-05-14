<?php

namespace App\Repositories\SQL;

use App\Models\City;
use App\Repositories\Contracts\CityContract;

class CityRepository extends BaseRepository implements CityContract
{
    public function __construct(City $model)
    {
        parent::__construct($model);
    }
}
