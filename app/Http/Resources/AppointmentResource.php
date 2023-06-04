<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AppointmentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $fulladdress = $this->business->address->description;
        $rated = $this->rating ? true : false;
        return [
            'id' => (string)$this->id,
            'date' => $this->date,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'status' => $this->status,
            'duration' => $this->duration,
            'total_price' => $this->total_price ?? $this->price,
            'rated' => $rated,
            'max_capacity' => $this->when($this->max_capacity !== null, function () {
                return $this->max_capacity;
            }),
            'count_ppl' => $this->count_ppl,
            'description' => $this->description,
            'title' => $this->title,
            'customer' => $this->customer ? [
                'data' => $this->customer,
                'phoneNumber' => $this->customer->user->phoneNumber,
            ] : [
                'name' => $this->name,
                'phoneNumber' => $this->phoneNumber
            ],
            'business' => [
                'data' => $this->business,
                'address' => $fulladdress,
                'phoneNumber' => $this->business->user->phoneNumber,
            ],
            'services' => $this->when($this->services !== null && $this->services->isNotEmpty(), function () {
                return [
                    'count' => $this->services->count(),
                    'data' => $this->services
                ];
            }),
        ];
    }
}
