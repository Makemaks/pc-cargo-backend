<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Services\AuthService;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;

class AuthController
{
    public function __construct(
        protected AuthService $service
    ) {}

    public function login(LoginRequest $request)
    {
        $result = $this->service->login(
            $request->email,
            $request->password
        );

        return response()->json([
            'user'  => new UserResource($result['user']),
            'token' => $result['token'],
        ]);
    }

    public function me()
    {
        return new UserResource(Auth::user());
    }

    public function logout()
    {
        $this->service->logout();
        return response()->noContent();
    }
}
