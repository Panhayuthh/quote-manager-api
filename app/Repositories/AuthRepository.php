<?php

namespace App\Repositories;

use App\Interfaces\AuthRepositoryInterface;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthRepository implements AuthRepositoryInterface
{
    /**
     * Create a new class instance.
     */

    public function register($data)
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return [
            'token' => $token,
            'user' => [ 'name' => $user->name, 'email' => $user->email ],
        ];
    }

    public function login($credentials)
    {
        $user = User::where('email', $credentials['email'])->first();

        if (! $user || !Hash::check($credentials['password'], $user->password)) {
            throw new \Exception('Password is incorrect');
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return [
            'token' => $token,
            'user' => [ 'name' => $user->name, 'email' => $user->email ],
        ];
    }

    public function logout($request)
    {
        $request->user()->currentAccessToken()->delete();

        return 'Logged out';
    }
}
