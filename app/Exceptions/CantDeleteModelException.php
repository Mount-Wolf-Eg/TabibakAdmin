<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CantDeleteModelException extends Exception
{
    /**
     * Render the exception as an HTTP response.
     *
     * @param Request $request
     * @return JsonResponse|RedirectResponse
     */
    public function render(Request $request): JsonResponse|RedirectResponse
    {
        if ($request->expectsJson()) {
            return response()->json([
                'status' => 422,
                'error' => $this->getMessage(),
                'message' => $this->getMessage(),
            ], 422);
        }
        return redirect()->back()->withInput(
            $request->input()
        );
    }
}
