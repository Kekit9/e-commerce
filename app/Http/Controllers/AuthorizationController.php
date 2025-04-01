<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthorizationRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class AuthorizationController extends Controller
{
    public function authorization(AuthorizationRequest $request): JsonResponse
    {
        $request->validated() && Auth::check();

        return response()->json([
            'redirect' => route('main')
        ]);

    }
}
