<?php

namespace App\Http\Controllers\Api\V1\Mobile;

use App\Http\Controllers\Api\V1\BaseApiController;
use App\Http\Resources\VendorResource;
use App\Repositories\Contracts\VendorContract;

class VendorController extends BaseApiController
{
    /**
     * VendorController constructor.
     * @param VendorContract $contract
     */
    public function __construct(VendorContract $contract)
    {
        $this->defaultScopes = ['active' => true];
        $this->relations = ['city', 'icon'];
        parent::__construct($contract, VendorResource::class);
    }
}
