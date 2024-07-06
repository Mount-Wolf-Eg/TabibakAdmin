<?php

namespace App\Http\Controllers\Api\V1\Mobile;

use App\Http\Controllers\Api\V1\BaseApiController;
use App\Http\Requests\DoctorScheduleDayRequest;
use App\Http\Requests\RateRequest;
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
        request()->merge(['order' => ['date'=> 'asc']]);
    }

    /**
     * Store a newly created resource in storage.
     * @param DoctorScheduleDayRequest $request
     * @return JsonResponse
     */
    public function store(DoctorScheduleDayRequest $request): JsonResponse
    {
        try {
            $doctorScheduleDay = $this->contract->create($request->validated());
            return $this->respondWithModel($doctorScheduleDay);
        }catch (Exception $e) {
            return $this->respondWithError($e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param DoctorScheduleDayRequest $request
     * @param DoctorScheduleDay $doctorScheduleDay
     * @return JsonResponse
     */
    public function update(DoctorScheduleDayRequest $request, DoctorScheduleDay $doctorScheduleDay) : JsonResponse
    {
        try {
        $doctorScheduleDay = $this->contract->update($doctorScheduleDay, $request->validated());
            return $this->respondWithModel($doctorScheduleDay);
        }catch (Exception $e) {
            return $this->respondWithError($e->getMessage());
        }
    }
    /**
     * Remove the specified resource from storage.
     * @param DoctorScheduleDay $doctorScheduleDay
     * @return JsonResponse
     */
    public function destroy(DoctorScheduleDay $doctorScheduleDay): JsonResponse
    {
        try {
            $this->contract->remove($doctorScheduleDay);
            return $this->respondWithSuccess(__('messages.actions_messages.delete_success'));
        }catch (Exception $e) {
            return $this->respondWithError($e->getMessage());
        }
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
