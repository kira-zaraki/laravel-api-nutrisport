<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\RegisterUserRequest;

class RegisterController extends Controller
{
    public function register(RegisterUserRequest $request)
    {
        try {
            $user = User::create([
                'name'=>$request->name,
                'email'=>$request->email,
                'password'=>Hash::make($request->password),
                'site_id' => $request->site_id,
            ]);

            $token = auth()->login($user);

            $response = [
                'user'=>$user->only('id','name','email','is_agent'),
                'token'=>$token
            ];
            return $this->success($response, 'Utilisateur enregistré avec succès');
        } catch (\Throwable $th) {
            return $this->error('Erreur lors de l\'enregistrement de l\'utilisateur', 500, $th->getMessage());
        }
    }
}
