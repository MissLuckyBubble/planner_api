<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAddressRequest;
use App\Http\Resources\AddressResource;
use App\Http\Resources\BusinessResource;
use App\Models\Address;
use App\Models\Business;
use App\Traits\HttpResponses;
use Illuminate\Support\Facades\Auth;

class AddressController extends Controller
{
    use HttpResponses;
    public function editAddress(StoreAddressRequest $request)
    {
        $address = Auth::user()->business->address;
        $request->validated($request->all());
        $address->update([
            'city' => $request->city,
            'street' => $request->street,
            'number'=> $request->number? $request->number : 0,
            'floor'=> $request->floor? $request->floor : 0,
            'postal_code' => $request->postal_code,
            'description'=> $request->description,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude
        ]);
        return $this->success(
            new BusinessResource(Auth::user()->business)
        );
    }
    public  function getAddress(){
        return AddressResource::collection(
            Address::where('id',Auth::user()->business->address->id)->get()
        );
    }

    public function getBusinessAddress(Business $business){
        return AddressResource::collection(
            Address::where('id',$business->address->id)->get()
        );
    }

}
