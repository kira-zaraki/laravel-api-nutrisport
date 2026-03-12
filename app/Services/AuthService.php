<?php

namespace App\Services;

use Auth;

class AuthService
{
    public function login(array $credentials)
    {
        if (! $token = Auth::attempt($credentials)) {
            throw new \Exception('Invalid credentials');
        }

        $user = Auth::user();

        $ttl = $user->is_agent ? 480 : 360;
        Auth::guard()->factory()->setTTL($ttl);

        return [
            'token' => $token,
            'user' => $user
        ];
    }
}