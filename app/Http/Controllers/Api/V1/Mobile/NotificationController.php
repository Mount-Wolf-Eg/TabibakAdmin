<?php

namespace App\Http\Controllers\Api\V1\Mobile;

use App\Http\Controllers\Api\V1\BaseApiController;
use App\Http\Controllers\Controller;
use App\Http\Resources\NotificationResource;
use App\Repositories\Contracts\NotificationContract;

class NotificationController extends BaseApiController
{
    /**
     * ComplaintController constructor.
     * @param NotificationContract $contract
     */
    public function __construct(NotificationContract $contract)
    {
        parent::__construct($contract, NotificationResource::class);
        if (!request('type'))
            abort(404);
        $this->defaultScopes = ['type' => request('type'), 'user' => auth('sanctum')->id()];
    }
}
