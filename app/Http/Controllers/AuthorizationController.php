<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthorizationRequest;
use App\Services\AuthorizationService;
use Illuminate\Http\JsonResponse;

class AuthorizationController extends Controller
{
    protected AuthorizationService $authorizationService;

    public function __construct(AuthorizationService $authorizationService)
    {
        $this->authorizationService = $authorizationService;
    }

    public function authorization(AuthorizationRequest $request): JsonResponse
    {
        $success = $this->authorizationService->attemptLogin($request->validated());

        return $success
            ? response()->json(['redirect' => route('main')])
            : response()->json(['message' => 'Invalid credentials'], 401);
    }
}
