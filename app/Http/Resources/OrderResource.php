<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
                'id' => $this->id,
                'client_name' => $this->user->name,
                'total' => $this->total,
                'status' => $this->status,
                'rest_to_pay' => $this->rest_to_pay,
                'items' => OrderItemResource::collection($this->items),
            ];
    }
}
