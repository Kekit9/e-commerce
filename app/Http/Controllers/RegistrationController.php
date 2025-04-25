<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Services\RegistrationService;
use Illuminate\Http\JsonResponse;

class RegistrationController extends Controller
{
    /**
     * RegistrationController constructor
     *
     * @param RegistrationService $registrationService The registration service
     */
    public function __construct(
        protected RegistrationService $registrationService
    ) {
    }

    /**
     * Call registerUser method inside service and return action value to switch page mode
     *
     * @param RegisterRequest $request
     *
     * @return JsonResponse
     */
    public function registration(RegisterRequest $request): JsonResponse
    {
        $this->registrationService->registerUser($request->validated());

        return response()->json([
            'message' => __('register.registered_successfully'),
            'action' => 'Login',
        ]);
    }
}
