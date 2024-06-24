<?php

namespace App\Http\Controllers\Api\V1\Mobile;

use App\Http\Controllers\Api\V1\BaseApiController;
use App\Http\Requests\ComplaintRequest;
use App\Http\Resources\ComplaintResource;
use App\Models\Complaint;
use App\Repositories\Contracts\ComplaintContract;
use Exception;
use Illuminate\Http\JsonResponse;

class ComplaintController extends BaseApiController
{
    /**
     * ComplaintController constructor.
     * @param ComplaintContract $contract
     */
    public function __construct(ComplaintContract $contract)
    {
        parent::__construct($contract, ComplaintResource::class);
    }
    /**
     * Store a newly created resource in storage.
     * @param ComplaintRequest $request
     * @return JsonResponse
     */
    public function store(ComplaintRequest $request): JsonResponse
    {
        try {
            $complaint = $this->contract->create($request->validated());
            return $this->respondWithModel($complaint->load($this->relations));
        }catch (Exception $e) {
            return $this->respondWithError($e->getMessage());
        }
    }
   /**
    * Display the specified resource.
    * @param Complaint $complaint
    * @return JsonResponse
    */
   public function show(Complaint $complaint): JsonResponse
   {
       try {
           return $this->respondWithModel($complaint->load($this->relations));
       }catch (Exception $e) {
           return $this->respondWithError($e->getMessage());
       }
   }
    /**
     * Update the specified resource in storage.
     *
     * @param ComplaintRequest $request
     * @param Complaint $complaint
     * @return JsonResponse
     */
    public function update(ComplaintRequest $request, Complaint $complaint) : JsonResponse
    {
        try {
            $complaint = $this->contract->update($complaint, $request->validated());
            return $this->respondWithModel($complaint->load($this->relations));
        }catch (Exception $e) {
            return $this->respondWithError($e->getMessage());
        }
    }
    /**
     * Remove the specified resource from storage.
     * @param Complaint $complaint
     * @return JsonResponse
     */
    public function destroy(Complaint $complaint): JsonResponse
    {
        try {
            $this->contract->remove($complaint);
            return $this->respondWithSuccess(__('messages.deleted'));
        }catch (Exception $e) {
            return $this->respondWithError($e->getMessage());
        }
    }
}
