<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePictureRequest;
use App\Models\Business;
use App\Models\Picture;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class PhotoController extends Controller
{
    use HttpResponses;

    public function upload(StorePictureRequest $request){

        $request->validated();

        $name = Carbon::now()->toDateTimeString() . '.' . $request->file('file')->getClientOriginalExtension();
        $path = Storage::path($request->file('file')->store('/public/files'));
        $path = str_replace('\\', '/', $path);

        $pic = Picture::create([
            'name' => $name,
            'path' => $path,
            'business_id' => Auth::user()->business->id
        ]);
        return $this->success([
            'picture' => $pic
        ]);
    }

    public function getAllPictures(){
        $pictures = Auth::user()->business->pictures;
        return response()->json(['pictures' => $pictures]);
    }

    public  function getPictureByBusiness(Business $business){
        $pictures = $business->pictures;
        return response()->json(['pictures' => $pictures]);
    }

    public function deletePictureFromBusiness(Picture $picture){
        return $this->isNotAuthorized($picture) ? $this->isNotAuthorized($picture) : $picture->delete();
    }

    private  function isNotAuthorized(Picture $picture){
        if(Auth::user()->business->id !== $picture->business->id){
            return $this->error('', 'You are not authorized to make this request', 403);
        }
    }
}
