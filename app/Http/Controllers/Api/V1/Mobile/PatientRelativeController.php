<?php

namespace App\Http\Controllers\Api\V1\Mobile;

use App\Http\Controllers\Api\V1\BaseApiController;
use App\Http\Requests\PatientRelativeRequest;
use App\Http\Resources\PatientResource;
use App\Models\Patient;
use App\Repositories\Contracts\PatientContract;
use Illuminate\Http\JsonResponse;

class PatientRelativeController extends BaseApiController
{
    public function __construct(PatientContract $contract)
    {
        parent::__construct($contract, PatientResource::class);
        $this->defaultScopes = ['parent' => auth('sanctum')->user()->patient?->id];
        $this->relations = ['diseases'];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param PatientRelativeRequest $request
     * @return mixed
     */
    public function store(PatientRelativeRequest $request)
    {
        $patient = $this->contract->create($request->validated());
        return $this->respondWithModel($patient->load('user'));
    }

    public function update(PatientRelativeRequest $request, Patient $relative)
    {
        if ($relative->parent_id !== auth()->user()->patient?->id)
            return $this->respondWithError(__('messages.actions_messages.cannot_do_action'), 422);
        $this->contract->update($relative, $request->validated());
        return $this->respondWithModel($relative->load('user'));
    }

    /**
     * Destroy the specified resource.
     *
     * @param Patient $relative
     * @return JsonResponse
     */
    public function destroy(Patient $relative)
    {
        if ($relative->parent_id !== auth()->user()->patient?->id)
            return $this->respondWithError(__('messages.actions_messages.cannot_do_action'), 422);
        $this->contract->remove($relative);
        return $this->respondWithSuccess(__('messages.actions_messages.delete_success'));
    }

}
