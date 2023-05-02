<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomDaysOffResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        $date = Carbon::createFromFormat('Y-m-d', $this->date);
        return [
            'id' => $this->id,
            'business' => [
                'id' => $this->business->id,
                'name' => $this->business->name,
            ],
            'date' => $date->format('d.m.y'),
        ];
    }
}
