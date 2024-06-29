<?php

namespace App\Http\Controllers\Api\V1\Mobile;

use App\Http\Controllers\Api\V1\BaseApiController;
use App\Http\Requests\DoctorScheduleDayRequest;
use App\Http\Requests\DoctorScheduleDayShiftRequest;
use App\Http\Requests\RateRequest;
use App\Http\Resources\DoctorScheduleDayResource;
use App\Http\Resources\DoctorScheduleDayShiftResource;
use App\Models\Doctor;
use App\Models\DoctorScheduleDay;
use App\Models\DoctorScheduleDayShift;
use App\Repositories\Contracts\DoctorScheduleDayContract;
use App\Repositories\Contracts\DoctorScheduleDayShiftContract;
use Exception;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class DoctorScheduleDayShiftController extends BaseApiController
{
    /**
     * DoctorScheduleDayController constructor.
     * @param DoctorScheduleDayShiftContract $contract
     */
    public function __construct(DoctorScheduleDayShiftContract $contract)
    {
        parent::__construct($contract, DoctorScheduleDayShiftResource::class);
    }

    /**
     * Store a newly created resource in storage.
     * @param DoctorScheduleDayShiftRequest $request
     * @return JsonResponse
     */
    public function store(DoctorScheduleDayShiftRequest $request): JsonResponse
    {
        try {
            $doctorScheduleDayShift = $this->contract->create($request->validated());
            return $this->respondWithModel($doctorScheduleDayShift);
        }catch (Exception $e) {
            return $this->respondWithError($e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param DoctorScheduleDayShiftRequest $request
     * @param DoctorScheduleDayShift $doctorScheduleDayShift
     * @return JsonResponse
     */
    public function update(DoctorScheduleDayShiftRequest $request, DoctorScheduleDayShift $doctorScheduleDayShift) : JsonResponse
    {
        try {
            $doctorScheduleDayShift = $this->contract->update($doctorScheduleDayShift, $request->validated());
            return $this->respondWithModel($doctorScheduleDayShift);
        }catch (Exception $e) {
            return $this->respondWithError($e->getMessage());
        }
    }
    /**
     * Remove the specified resource from storage.
     * @param DoctorScheduleDayShift $doctorScheduleDayShift
     * @return JsonResponse
     */
    public function destroy(DoctorScheduleDayShift $doctorScheduleDayShift): JsonResponse
    {
        try {
            $doctorScheduleDayShift->slots()->delete();
            $this->contract->remove($doctorScheduleDayShift);
            return $this->respondWithSuccess(__('messages.actions_messages.delete_success'));
        }catch (Exception $e) {
            return $this->respondWithError($e->getMessage());
        }
    }
}
