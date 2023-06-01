<?php

namespace App\Http\Controllers;

use App\Http\Requests\setCategoryToBusinessRequest;
use App\Http\Resources\BusinessHasCategoryResource;
use App\Models\Business;
use App\Models\Category;
use App\Models\BusinessHasCategory;
use App\Traits\HttpResponses;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    use HttpResponses;
   public function index(){
       return Category::all();
   }

   public function getBusinessCategories(){
       return BusinessHasCategoryResource::collection(
           Auth::user()->business->businessHasCategories
       );
   }

    public function getCategoriesByBusiness(Business $business){
        return BusinessHasCategoryResource::collection(
            $business->businessHasCategories
        );
    }

    public function setCategoryToBusiness(setCategoryToBusinessRequest $request)
    {
        $validatedData = $request->validated();
        $business = Auth::user()->business;

        $categoryIds = explode(',', $validatedData['category_id']);

        collect($categoryIds)->map(function ($categoryId) use ($business) {
            $bhc = BusinessHasCategory::where('business_id', $business->id)
                ->where('category_id', $categoryId)->first();
            if ($bhc == null) {
                BusinessHasCategory::create([
                    'business_id' => $business->id,
                    'category_id' => $categoryId]);
            }
        });

        return BusinessHasCategoryResource::collection($business->businessHasCategories);
    }

   public function deleteCategoryFromBusiness(setCategoryToBusinessRequest $request){
       $validatedData = $request->validated();
       $business = Auth::user()->business;
       $categoryIds = explode(',', $validatedData['category_id']);

       $hasError = false;
       collect($categoryIds)->map(function ($categoryId) use ($business, &$hasError) {
           $bhc = BusinessHasCategory::where('business_id', $business->id)
               ->where('category_id', $categoryId)->first();
           if ($bhc == null) {
               $hasError = true;
           }
       });

       if ($hasError) {
           return $this->error('', 'You are not authorized to make this request', 403);
       }

       collect($categoryIds)->each(function ($categoryId) use ($business) {
           BusinessHasCategory::where('category_id', $categoryId)
               ->where('business_id', $business->id)
               ->delete();
       });

       return BusinessHasCategoryResource::collection($business->businessHasCategories);
   }

}
