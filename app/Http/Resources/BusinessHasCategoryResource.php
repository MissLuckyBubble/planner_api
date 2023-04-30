<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BusinessHasCategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' =>(string)$this->id,
            'business' => [
                'id' => (string)$this->business->id,
                'name' => $this->business->name
            ],
            'category'=>[
                'id' => (string)$this->category->id,
                'title' => $this->category->title,
                'description' => $this->category->description
            ]
        ];
    }
}
