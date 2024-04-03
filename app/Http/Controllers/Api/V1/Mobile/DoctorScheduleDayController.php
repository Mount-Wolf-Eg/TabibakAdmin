<?php

namespace App\Http\Controllers\Api\V1\Mobile;

use App\Http\Controllers\Api\V1\BaseApiController;
use App\Http\Requests\DoctorScheduleDayRequest;
use App\Http\Resources\DoctorScheduleDayResource;
use App\Models\DoctorScheduleDay;
use App\Repositories\Contracts\DoctorScheduleDayContract;
use Exception;
use Illuminate\Http\JsonResponse;

class DoctorScheduleDayController extends BaseApiController
{
    /**
     * DoctorScheduleDayController constructor.
     * @param DoctorScheduleDayContract $contract
     */
    public function __construct(DoctorScheduleDayContract $contract)
    {
        parent::__construct($contract, DoctorScheduleDayResource::class);
        $this->relations = ['availableSlots'];
    }
}
