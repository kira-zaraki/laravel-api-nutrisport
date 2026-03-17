<?php

namespace App\Http\Controllers\Ai;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Ai\Agents\NutritionAdvisorAgent;

class AIController extends Controller
{
    public function chat(Request $request)
    {
        $request->validate([
            'message' => 'required|string',
            'site_id' => 'required|exists:sites,id',
            'cart_id'=>'required|string'
        ]);

        $response = (new NutritionAdvisorAgent($request->site_id, $request->cart_id))
        ->forUser(auth()->user())
        ->prompt($request->message);

        return $this->success($response->text, 'Response generated successfully');
    }
}
