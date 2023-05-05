<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerResource extends JsonResource
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
            'info' => [
                'name' => $this->name,
                'birth_day' => $this->birth_day,
                'age' => Carbon::parse($this->birth_day)->diffInYears(Carbon::now()),
                'sex' => $this->sex,
            ],
            'user' => [
                'user_id' => (string)$this->user->id,
                'email' => $this->user->email,
                'phoneNumber'=> '0' . $this->user->phoneNumber,
            ]
        ];
    }
}
