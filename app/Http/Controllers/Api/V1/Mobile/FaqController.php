<?php

namespace App\Http\Controllers\Api\V1\Mobile;

use App\Http\Controllers\Api\V1\BaseApiController;
use App\Http\Resources\FaqResource;
use App\Repositories\Contracts\FaqContract;

class FaqController extends BaseApiController
{
    /**
     * FaqController constructor.
     * @param FaqContract $contract
     */
    public function __construct(FaqContract $contract)
    {
        parent::__construct($contract, FaqResource::class);
        $this->relations = ['faqSubject'];
        $this->defaultScopes = ['active' => true, 'activeSubject' => true];
    }
}
