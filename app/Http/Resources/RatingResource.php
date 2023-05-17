<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RatingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [

            'id' => (string)$this->id,
            'rating' =>$this->rate,
            'comment' => $this->comment,
            'customer' => [
                'name' =>$this->customer->name,
                'email' =>$this->customer->user->email,
            ]
        ];
    }
}
