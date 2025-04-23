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
     * @param Request $request
     * @param Closure(Request): (Response) $next
     *
     * @return JsonResponse|Response
     */
    public function handle(Request $request, Closure $next): JsonResponse|Response
    {
        $route = $request->route();

        if ($route && $route->getActionMethod() === 'index') {
            return $next($request);
        }

        $user = auth()->user();

        if ($user && $user->role === 'admin') {
            return $next($request);
        }

        return response()->json(['error' => __('auth.error')]);
    }
}
