<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\AuthorizationRequest;
use App\Services\AuthorizationService;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\JsonResponse;

class AuthorizationController extends Controller
{
    /**
     * @param AuthorizationService $authorizationService
     */
    public function __construct(
        protected AuthorizationService $authorizationService
    ) {
    }

    /**
     * @param AuthorizationRequest $request
     *
     * @return JsonResponse
     *
     * @throws AuthenticationException
     */
    public function authorization(AuthorizationRequest $request): JsonResponse
    {
        $result = $this->authorizationService->attemptLogin($request->validated());

        return response()->json([
            ...$result,
            'redirect' => route('main')
        ]);
    }
}
