<?php

namespace App\Http\Controllers\Api\V1\Mobile;

use App\Http\Controllers\Api\V1\BaseApiController;
use App\Http\Requests\ContactRequest;
use App\Http\Resources\ContactResource;
use App\Models\Contact;
use App\Repositories\Contracts\ContactContract;
use Exception;
use \Illuminate\Http\JsonResponse;

class ContactController extends BaseApiController
{
    /**
     * ContactController constructor.
     * @param ContactContract $contract
     */
    public function __construct(ContactContract $contract)
    {
        parent::__construct($contract, ContactResource::class);
    }
    /**
     * Store a newly created resource in storage.
     * @param ContactRequest $request
     * @return JsonResponse
     */
    public function store(ContactRequest $request): JsonResponse
    {
        try {
            $contact = $this->contract->create($request->validated());
            return $this->respondWithModel($contact);
        }catch (Exception $e) {
            return $this->respondWithError($e->getMessage());
        }
    }
}
