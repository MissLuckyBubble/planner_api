<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRateRequest;
use App\Http\Resources\RatingResource;
use App\Models\Appointment;
use App\Models\Business;
use App\Models\Rating;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RatingController extends Controller
{
    use HttpResponses;
    public function leaveRate(StoreRateRequest $request, Appointment $appointment){
        $customer = Auth::user()->customer;
        if($customer->id != $appointment->customer->id){
            return $this->error('', 'You are not authorized to make this request', 403);
        }else if (Rating::where('appointment_id', $appointment->id)->exists()) {
            return $this->error('', 'This appointment already has a rate', 400);
        } else if($appointment->status != 'Приключен') {
            return $this->error('', 'Only finished appointments can be rated', 400);
        }
        $rate = Rating::create([
            'appointment_id' => $appointment->id,
            'business_id' => $appointment->business->id,
            'customer_id' => $customer->id,
            'rate' => $request->rate,
            'comment' => $request->comment
        ]);
        $business = $appointment->business;
        $business->rating = $business->ratings->avg('rate');
        $business->save();
        return $this->success($rate, 'Success', 200);
    }

    public function getBusinessRatingAndComments(Business $business){
        $ratings = $business->ratings;
        $averageRating = $ratings->avg('rate');
        return [
            'average_rating' => $averageRating,
            'comments' => RatingResource::collection($ratings),
        ];
    }

    public function businessGetsHisRatesAndComments(){
        $business = Auth::user()->business;
        $ratings = $business->ratings;
        $averageRating = $ratings->avg('rate');

        return [
            'average_rating' => $averageRating,
            'comments' => RatingResource::collection($ratings),
        ];
    }
}
