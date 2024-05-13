<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class DoctorActiveMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->user()->doctor?->is_active) {
            return response()->json(['message' => __('messages.not_active_account')], 403);
        }
        return $next($request);
    }
}
