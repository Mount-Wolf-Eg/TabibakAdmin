<?php

namespace App\Http\Controllers\Api\V1\Mobile;

use App\Http\Controllers\Api\V1\BaseApiController;
use App\Http\Requests\DoctorScheduleDayRequest;
use App\Http\Resources\DoctorScheduleDayResource;
use App\Models\Doctor;
use App\Models\DoctorScheduleDay;
use App\Repositories\Contracts\DoctorScheduleDayContract;
use Exception;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

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
        $this->defaultScopes = ['afterNowDateTime' => true];
    }

    public function nearestAvailableDay(Doctor $doctor)
    {
        $scopes = array_merge($this->defaultScopes, ['doctor' => $doctor->id, 'has' => 'availableSlots']);
        $this->relations = ['nearestAvailableSlot'];
        $day = $this->contract->findByFilters($scopes);
        if (!$day) {
            return $this->respondWithError(__('messages.no_slots_available'), Response::HTTP_NOT_FOUND);
        }
        return $this->respondWithModel($day);
    }
}
