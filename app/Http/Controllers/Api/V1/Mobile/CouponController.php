<?php

namespace App\Http\Controllers\Api\V1\Mobile;

use App\Http\Controllers\Api\V1\BaseApiController;
use App\Http\Controllers\Controller;
use App\Http\Resources\CouponResource;
use App\Repositories\Contracts\CouponContract;

class CouponController extends BaseApiController
{
    public function __construct(CouponContract $contract)
    {
        parent::__construct($contract, CouponResource::class);
    }
}
