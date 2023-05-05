<?php

namespace App\Http\Controllers;

use App\Http\Requests\CustomerRequest;
use App\Http\Resources\CustomerResource;
use App\Models\Business;
use App\Models\Customer;
use App\Models\Customer_Has_Favorite_Business;
use App\Models\User;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{
    use HttpResponses;
    public function getProfile(){
        $customer = Auth::user()->customer;
        return new CustomerResource($customer);
    }

    public function editProfile(CustomerRequest $request){
        $customer = Auth::user()->customer;
        $request->validated($request->all());

        $customer->update([
            'name' => $request->name,
            'sex' => $request->sex,
            'birth_day'=> $request->birth_day
        ]);


        $customer = new CustomerResource($customer);

        return $this->success([
           'customer'=>$customer
        ]);
    }

    public function add_delete_FavoritePlace(Business $business){
        $customer = Auth::user()->customer;
        $customer_has_fav_bus = Customer_Has_Favorite_Business::where(
            ['customer_id', 'business_id'], [$customer->id, $business->id])->findOrFail();
        if($customer_has_fav_bus){
            return $customer_has_fav_bus->delete();
        }
        $customer_has_fav_bus =Customer_Has_Favorite_Business::create([
            'customer_id' => $customer->id,
            'business_id' => $business->id
        ]);
        return $this->success([
            'id' => $customer_has_fav_bus->id,
            'customer'=>$customer,
            'business' => $business
        ],'success',200);
    }


}
