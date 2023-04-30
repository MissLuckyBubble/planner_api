<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AddressResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $fulladdress= $this->city . ', ' . $this->street . ' ' . $this->number . ', етаж: ' . $this->floor;
        return [
            'id' => (string)$this->id,
            'address' => [
                'full' => $fulladdress,
                'city' => $this->city,
                'street' => $this->street,
                'number' => $this->number,
                'floor' => $this->floor,
                'description' => $this->description
            ],
            'business' => [
                'id' => (string)$this->business->id,
                'name' => $this->business->name
            ]
        ];
    }
}
