<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BusinessResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $fulladdress = $this->address->city . ', ' . $this->address->street . ' ' . $this->address->number . ', етаж: ' . $this->address->floor;
        return [
            'id' => (string)$this->id,
            'name' => $this->name,
            'address' => $fulladdress,
            'phoneNumber' =>$this->user->phoneNumber,
            'rating' => $this->rating,
            'review_number' => count($this->ratings),
            'business_category' => $this->businessHasCategories->map(function ($businessHasCategory) {
                return [
                    'id' => $businessHasCategory->category->id,
                    'title' => $businessHasCategory->category->title,
                ];
            }),
            'services_category' => $this->service_categories->map(function ($serviceCategory) {
                return [
                    'id' => (string)$serviceCategory->id,
                    'title' => $serviceCategory->title,
                    'services' => $serviceCategory->services,
                ];
            }),
            'comments' => $this->ratings->map(function ($rating){
                return [
                    'id' => (string)$rating->id,
                    'comment' => $rating->comment,
                    'rate' => $rating->rate,
                    'customer_name' => $rating->customer->name,
                    'customer_sex' => $rating->customer->sex,
                    'customer_age' => Carbon::parse($rating->customer->birth_day)->age,
                    'date' => $rating->created_at->format('d.m.y')
                ];
            }),
            'picture'=> $this->pictures,
        ];
    }
}
