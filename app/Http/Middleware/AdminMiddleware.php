<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): (Response) $next
     *
     * @return JsonResponse|mixed|Response
     */
    public function handle($request, Closure $next)
    {
        if ($request->route()->getActionMethod() === 'index') {
            return $next($request);
        }

        if (auth()->check() && auth()->user()->role === 'admin') {
            return $next($request);
        }

        return response()->json(['error' => __('auth.error')]);
    }
}
