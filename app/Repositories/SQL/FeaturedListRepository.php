<?php

namespace App\Repositories\SQL;

use App\Models\FeaturedList;
use App\Repositories\Contracts\FeaturedListContract;

class FeaturedListRepository extends BaseRepository implements FeaturedListContract
{
    /**
     * FeaturedListRepository constructor.
     * @param FeaturedList $model
     */
    public function __construct(FeaturedList $model)
    {
        parent::__construct($model);
    }
}
