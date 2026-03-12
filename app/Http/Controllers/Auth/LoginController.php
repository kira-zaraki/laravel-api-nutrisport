<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\AuthService;
use Auth;

class LoginController extends Controller
{
    public function __construct(
        protected AuthService $authService,
    ) {}

    public function login(Request $request)
    {
        $request->validate([
            'email'=>'required|email',
            'password'=>'required|string',
            'site_id'=>'required|exists:sites,id',
        ]);

        $credentials = [
            'email' => $request->email,
            'password' => $request->password,
            'site_id' => $request->site_id,
        ];

        try {
            $data = $this->authService->login($credentials);
            $token = $this->respondWithToken($data['token'], $data['user']);
            return $this->success($token, 'Connexion réussie');
        } catch (\Exception $e) {
            return $this->error('Identifiants invalides', 401);
        }
    }

    protected function respondWithToken($token, $user = null)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => Auth::guard()->factory()->getTTL() * 60,
            'user' => $user ? $user->only('id','name','email','is_agent') : null
        ]);
    }
}
