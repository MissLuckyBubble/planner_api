<?php

namespace App\Http\Controllers;

use App\Http\Requests\setCategoryToBusinessRequest;
use App\Http\Resources\BusinessHasCategoryResource;
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
           BusinessHasCategory::where('business_id',Auth::user()->business->id)->get()
       );
   }

   public function setCategoryToBusiness(setCategoryToBusinessRequest $request){
       $request->validated($request->all());
       $category = BusinessHasCategory::create([
          'business_id' => Auth::user()->business->id,
           'category_id' => $request->category_id
       ]);
       return new BusinessHasCategoryResource($category);
   }

   public function deleteCategoryFromBusiness(BusinessHasCategory $businessHasCategory){
       return $this->isNotAuthorized($businessHasCategory) ? $this->isNotAuthorized($businessHasCategory) : $businessHasCategory->delete();
   }

   private  function isNotAuthorized(BusinessHasCategory $businessHasCategory){
       if(Auth::user()->business->id !== $businessHasCategory->business->id){
            return $this->error('', 'You are not authorized to make this request', 403);
       }
   }
}
