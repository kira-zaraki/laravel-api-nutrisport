<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\UpdateProfileRequest;
use App\Services\UserService;

class UserController extends Controller
{
    public function updateProfile(
            UpdateProfileRequest $request, 
            UserService $userService
    ){
        $user = auth()->user();

        $updatedUser = $userService->updateProfile($user, $request->validated());

        return $this->success($updatedUser->only('id','name','email'), 'Profil mis à jour avec succès');
    }
}
