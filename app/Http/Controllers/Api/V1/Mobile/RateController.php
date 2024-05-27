<?php

namespace App\Http\Controllers\Api\V1\Mobile;

use App\Http\Controllers\Api\V1\BaseApiController;
use App\Http\Requests\RateRequest;
use App\Http\Resources\RateResource;
use App\Models\Rate;
use App\Repositories\Contracts\RateContract;
use Exception;
use Illuminate\Http\JsonResponse;

class RateController extends BaseApiController
{
    /**
     * RateController constructor.
     * @param RateContract $contract
     */
    public function __construct(RateContract $contract)
    {
        parent::__construct($contract, RateResource::class);
    }
    /**
     * Store a newly created resource in storage.
     * @param RateRequest $request
     * @return JsonResponse
     */
    public function store(RateRequest $request): JsonResponse
    {
        try {
            $rate = $this->contract->create($request->validated());
            return $this->respondWithModel($rate->load($this->relations));
        }catch (Exception $e) {
            return $this->respondWithError($e->getMessage());
        }
    }
    /**
     * Update the specified resource in storage.
     *
     * @param RateRequest $request
     * @param Rate $rate
     * @return JsonResponse
     */
    public function update(RateRequest $request, Rate $rate) : JsonResponse
    {
        try {
            $rate = $this->contract->update($rate, $request->validated());
            return $this->respondWithModel($rate->load($this->relations));
        }catch (Exception $e) {
            return $this->respondWithError($e->getMessage());
        }
    }
    /**
     * Remove the specified resource from storage.
     * @param Rate $rate
     * @return JsonResponse
     */
    public function destroy(Rate $rate): JsonResponse
    {
        try {
            $this->contract->remove($rate);
            return $this->respondWithSuccess(__('messages.deleted'));
        }catch (Exception $e) {
            return $this->respondWithError($e->getMessage());
        }
    }
}
