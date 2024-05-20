<?php

namespace App\Traits;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

trait JsonValidationTrait
{
    use BaseApiResponseTrait;
    protected function failedValidation(Validator $validator): void
    {
        if (request()->wantsJson()) {
            $errors = $validator->errors();
            throw new HttpResponseException(response()->json([
                'status' => 422,
                'message' => __('validation.failed'),
                'errors' => $errors,
            ], ResponseAlias::HTTP_UNPROCESSABLE_ENTITY));
        }
        parent::failedValidation($validator);
    }
}
