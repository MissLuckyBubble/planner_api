<?php

namespace App\Http\Controllers;

use App\Http\Requests\CustomerRequest;
use App\Http\Requests\getAllBusinessesRequest;
use App\Http\Resources\BusinessResource;
use App\Http\Resources\CustomerResource;
use App\Models\Business;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BusinessController extends Controller
{
    use HttpResponses;

    public function getProfile()
    {
        $business = Auth::user()->business;
        if ($business) {
            return new BusinessResource($business);
        } else return $this->error('','',400);
    }


    public function editProfile(Request $request)
    {
        $business = Auth::user()->business;

        $request->validate([
            'name' => 'sometimes|string|max:100',
            'description' => 'sometimes|string|max:500'
        ]);


        if($request->has('name')){
            $business->name = $request->name;
        }

        if($request->has('description')){
            $business->description = $request->description;
        }

        $business->save();

        return $this->success($business);
    }

    public function getAllBusinesses(getAllBusinessesRequest $request)
    {
        $request->validated($request->all());

        $page = $request->get('page', 1);
        $perPage = $request->get('per_page', 10);

        $query = Business::query();

        if ($request->has('search') && $request->search != '') {
            $query->where(function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->get('search') . '%')
                    ->orWhereHas('services', function ($serviceQuery) use ($request) {
                        $serviceQuery->where('title', 'like', '%' . $request->get('search') . '%')
                            ->orWhere('description', 'like', '%' . $request->get('search') . '%');
                    });
            });
        }

        if ($request->has('city') && $request->city != '') {
            $query->whereHas('address', function ($addressQuery) use ($request) {
                $addressQuery->where('description', 'like', '%' . $request->get('city') . '%' );
            });
        }

        if ($request->has('rating') && $request->reating != '') {
            $query->where('rating', '=',$request->get('rating'));
        }

        if ($request->has('category') && $request->category != '') {
            $categoryIds = explode(',', $request->category);
            $query->whereHas('businessHasCategories', function ($categoryQuery) use ($categoryIds) {
                $categoryQuery->whereIn('business_has_categories.category_id', $categoryIds);
            });
        }

        if ($request->has('sortBy') && $request->has('sortOrder')
            && $request->sortBy != '' && $request->sortOrder != '') {
            $sortBy = $request->get('sortBy');
            $sortOrder = $request->get('sortOrder');

            switch ($sortBy) {
                case 'Име':
                    $query->orderBy('name', $sortOrder);
                    break;
                case 'Рейтинг':
                    $query->orderBy('rating', $sortOrder);
                    break;
                case 'Цена':
                    $query->orderBy('price', $sortOrder);
                    break;
            }
        }

        // Paginate the results
        $businesses = $query->paginate($perPage, ['*'], 'page', $page);

        return $this->success([
            BusinessResource::collection($businesses)
        ]);
    }
    public function getBusiness(Business $business){
        return $this->success([
            new BusinessResource($business)
        ]);
    }


}
