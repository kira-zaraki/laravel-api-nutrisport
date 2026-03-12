<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RefreshController extends Controller
{
    public function refresh()
    {
        $newToken = auth()->refresh();

        $response = [
            'access_token'=>$newToken,
            'token_type'=>'bearer',
            'expires_in'=>auth()->factory()->getTTL()*60
        ];

        return $this->success($response, 'Token actualisé avec succès');
    }
}
