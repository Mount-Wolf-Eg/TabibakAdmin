<?php

namespace App\Http\Controllers\Api\V1\Mobile;

use App\Http\Controllers\Api\V1\BaseApiController;
use App\Http\Requests\FeaturedListRequest;
use App\Http\Resources\FeaturedListResource;
use App\Models\FeaturedList;
use App\Repositories\Contracts\FeaturedListContract;
use Exception;
use \Illuminate\Http\JsonResponse;

class FeaturedListController extends BaseApiController
{
    /**
     * FeaturedListController constructor.
     * @param FeaturedListContract $contract
     */
    public function __construct(FeaturedListContract $contract)
    {
        parent::__construct($contract, FeaturedListResource::class, 'FeaturedList');
    }
    /**
     * Store a newly created resource in storage.
     * @param FeaturedListRequest $request
     * @return JsonResponse
     */
    public function store(FeaturedListRequest $request): JsonResponse
    {
        try {
            $featuredList = $this->contract->create($request->validated());
            return $this->respondWithModel($featuredList->load($this->relations));
        }catch (Exception $e) {
            return $this->respondWithError($e->getMessage());
        }
    }
   /**
    * Display the specified resource.
    * @param FeaturedList $featuredList
    * @return JsonResponse
    */
   public function show(FeaturedList $featuredList): JsonResponse
   {
       try {
           return $this->respondWithModel($featuredList->load($this->relations));
       }catch (Exception $e) {
           return $this->respondWithError($e->getMessage());
       }
   }
    /**
     * Update the specified resource in storage.
     *
     * @param FeaturedListRequest $request
     * @param FeaturedList $featuredList
     * @return JsonResponse
     */
    public function update(FeaturedListRequest $request, FeaturedList $featuredList) : JsonResponse
    {
        try {
            $featuredList = $this->contract->update($featuredList, $request->validated());
            return $this->respondWithModel($featuredList->load($this->relations));
        }catch (Exception $e) {
            return $this->respondWithError($e->getMessage());
        }
    }
    /**
     * Remove the specified resource from storage.
     * @param FeaturedList $featuredList
     * @return JsonResponse
     */
    public function destroy(FeaturedList $featuredList): JsonResponse
    {
        try {
            $this->contract->remove($featuredList);
            return $this->respondWithSuccess(__('messages.deleted'));
        }catch (Exception $e) {
            return $this->respondWithError($e->getMessage());
        }
    }

    /**
     * active & inactive the specified resource from storage.
     * @param FeaturedList $featuredList
     * @return JsonResponse
     */
    public function changeActivation(FeaturedList $featuredList): JsonResponse
    {
        try {
            $this->contract->toggleField($featuredList, 'is_active');
            return $this->respondWithModel($featuredList->load($this->relations));
        }catch (Exception $e) {
            return $this->respondWithError($e->getMessage());
        }
    }
}
