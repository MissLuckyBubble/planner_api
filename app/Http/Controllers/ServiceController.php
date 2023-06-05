<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreServiceRequest;
use App\Http\Resources\ServiceCategoryResource;
use App\Models\Business;
use App\Models\GroupAppointment;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PHPUnit\Metadata\Group;

class ServiceController extends Controller
{
    use HttpResponses;

    public function isNotAuthorized(ServiceCategory $serviceCategory)
    {
        if (Auth::user()->business->id !== $serviceCategory->business->id) {
            return $this->error('', 'You are not authorized to make this request', 403);
        }
    }

    public function createServiceCategory(Request $request)
    {
        $business = Auth::user()->business;
        $serviceCategory = ServiceCategory::create([
            'title' => $request->title,
            'business_id' => $business->id
        ]);
        return $this->success([
            'Service Category' => $serviceCategory
        ]);
    }

    public function editServiceCategory(Request $request, ServiceCategory $serviceCategory)
    {
        if ($this->isNotAuthorized($serviceCategory))
            return $this->isNotAuthorized($serviceCategory);
        if (!$request->title)
            return $this->error('', 'Заглавието е задължително поле', 422);
        $serviceCategory->update([
            'title' => $request->title
        ]);
        return $this->success([
            'service_category' => $serviceCategory,
            'services' => $serviceCategory->services
        ]);
    }

    public function getAllServiceCategory()
    {
        return ServiceCategoryResource::collection(
            ServiceCategory::where(
                'business_id', Auth::user()->business->id)->get());
    }

    public function getServiceCategory(ServiceCategory $serviceCategory)
    {
        return ServiceCategoryResource::collection(ServiceCategory::where(
            'id', $serviceCategory->id)->get());
    }

    public function getAllServiceCategoryByBusiness(Business $business)
    {
        return ServiceCategoryResource::collection(
            ServiceCategory::where('business_id', $business->id)->get());
    }

    public function deleteServiceCategory(ServiceCategory $serviceCategory)
    {
        if ($this->isNotAuthorized($serviceCategory))
            return $this->isNotAuthorized($serviceCategory);
        else return $serviceCategory->delete();
    }

    public function createService(StoreServiceRequest $request)
    {
        $serviceCategory = ServiceCategory::findOrFail($request->service_category_id);
        if ($this->isNotAuthorized($serviceCategory))
            return $this->isNotAuthorized($serviceCategory);

        $request->validated($request->all());

        if (Auth::user()->business != null) {
            $service = Service::create([
                'title' => $request->title,
                'description' => $request->description,
                'price' => $request->price,
                'duration' => $request->duration,
                'service_category_id' => $request->service_category_id,
                'business_id' => Auth::user()->business->id
            ]);
        } else {
            return $this->error('User does not have a business');
        }

        return $this->success([
            'Service' => [
                'title' => $service->title,
                'description' => $service->description,
                'price' => $service->price,
                'duration' => $service->duration,
                'service_category_id' => $service->service_category_id],
            'Category' => $service->service_category
        ]);
    }



    public function editService(StoreServiceRequest $request, Service $service)
    {
        $serviceCategory = ServiceCategory::findOrFail($service->service_category_id);
        if ($this->isNotAuthorized($serviceCategory))
            return $this->isNotAuthorized($serviceCategory);
        $serviceCategory = ServiceCategory::findOrFail($request->service_category_id);
        if ($this->isNotAuthorized($serviceCategory))
            return $this->isNotAuthorized($serviceCategory);
        $request->validated($request->all());
        $service->update([
            'title' => $request->title,
            'description' => $request->description,
            'price' => $request->price,
            'duration' => $request->duration,
            'service_category_id' => $request->service_category_id,
        ]);
        return $this->success([
            'Service' => [
                'id' => $service->id,
                'description' => $service->description,
                'price' => $service->price . ' BGN',
                'duration' => $service->duration,
            ],
            'Category' => $service->service_category
        ]);
    }

    public function moveServiceToNewCategory(Service $service, Request $request)
    {
        $serviceCategory = ServiceCategory::findOrFail($service->service_category_id);
        if ($this->isNotAuthorized($serviceCategory))
            return $this->isNotAuthorized($serviceCategory);
        $serviceCategory = ServiceCategory::findOrFail($request->id);
        if ($this->isNotAuthorized($serviceCategory))
            return $this->isNotAuthorized($serviceCategory);
        if ($request->id == null || $request->id == '') {
            return $this->error('', 'Вашата заявка не моеже да бъде обработена', 422);
        }
        $service->update(['service_category_id' => $request->id]);
        return $this->success([
            'Service' => $service,
            'Category' => $service->service_category
        ]);
    }


    public function disableService(Service $service)
    {
        $serviceCategory = ServiceCategory::findOrFail($service->service_category_id);
        $isNotAuthorized = $this->isNotAuthorized($serviceCategory);
        if ($isNotAuthorized) {
            return $isNotAuthorized;
        }
        $service->update(['disabled' => true]);
        return $this->success($service, '', 200);
    }

    public function getAllServices(Request $request){
        $query = Service::query();

        if ($request->has('title')) {
            $query->where('title', 'like', '%' . $request->get('title') . '%')
                ->orWhere('description', 'like', '%' . $request->get('title') . '%');
        }
        $services =$query->get();

        return $this->success([
            $services
        ]);
    }
}
