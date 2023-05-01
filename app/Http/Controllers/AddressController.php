<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAddressRequest;
use App\Http\Resources\AddressResource;
use App\Models\Address;
use App\Models\Business;
use App\Models\BusinessHasCategory;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
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
            'number'=> $request->number,
            'floor'=> $request->floor,
            'postal_code' => $request->postal_code,
            'description'=> $request->description
        ]);
        return $this->success([
            'address' => $address,
        ]);
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
