<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Services\AuthService;
use Illuminate\Http\Response;

class AuthController extends Controller
{
    protected AuthService $service;

    public function __construct(AuthService $service)
    {
        $this->service = $service;
    }

    public function register(RegisterRequest $request):Response
    {
        $userData = $request->validated();

        return $this->service->register($userData);
    }

    public function login(LoginRequest $request):Response
    {
        $credentials = $request->validated();

        return $this->service->login($credentials);
    }
}
