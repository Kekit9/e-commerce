<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthorizationRequest;
use App\Services\AuthorizationService;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\JsonResponse;

class AuthorizationController extends Controller
{
    /**
     * The AuthorizationService service instance
     *
     * @var AuthorizationService
     */
    protected AuthorizationService $authorizationService;

    /**
     * @param AuthorizationService $authorizationService
     */
    public function __construct(AuthorizationService $authorizationService)
    {
        $this->authorizationService = $authorizationService;
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
