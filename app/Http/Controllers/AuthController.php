<?php

namespace App\Http\Controllers;

use App\Classes\ApiResponseClass;
use App\Http\Requests\LoginAuthRequest;
use App\Http\Requests\RegisterAuthRequest;
use App\Interfaces\AuthRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    // inject the AuthRepositoryInterface dependency
    private AuthRepositoryInterface $authRepository;

    public function __construct(AuthRepositoryInterface $authRepository)
    {
        $this->authRepository = $authRepository;
    }

    public function register(RegisterAuthRequest $register)
    {
        $data = [
            'name' => $register->name,
            'email' => $register->email,
            'password' => $register->password,
        ];

        DB::beginTransaction();
        try {
            $user = $this->authRepository->register($data);
            DB::commit();
            return ApiResponseClass::sendResponse($user, 'User registered successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            ApiResponseClass::sendError($e, 'An error occurred while registering user', 401);
        }
    }

    public function login(LoginAuthRequest $request)
    {
        $credentials = $request->only('email', 'password');

        DB::beginTransaction();
        try {
            $user = $this->authRepository->login($credentials);
            DB::commit();
            return ApiResponseClass::sendResponse($user, 'Login successful');
        } catch (\Exception $e) {
            DB::rollBack();
            ApiResponseClass::sendError($e, 'An error occurred while logging in user', 401);
        }
    }

    public function logout(Request $request)
    {
        $this->authRepository->logout($request);
        return ApiResponseClass::sendResponse([], 'User logged out successfully');
    }
}
