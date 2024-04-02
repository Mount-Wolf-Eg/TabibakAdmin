<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\App;

class Localization
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param  \Closure(Request): (Response|RedirectResponse)  $next
     * @return Response|RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        /* Set new lang with the use of session */
        if (session()->has('lang')) {
            App::setLocale(session('lang'));
        }
        if ($request->header('X-localization')) {
            App::setLocale($request->header('X-localization'));
        }
        return $next($request);
    }
}
