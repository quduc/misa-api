<?php

namespace App\Http\Controllers\User;

use App\Helpers\MetaSeoRender;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\Car\CarRequest;
use App\Services\Api\CarService;
use App\Services\Api\ProvinceService;
use App\Services\Api\SearchHistoryService;
use App\Services\Api\OrderService;

class CarController extends Controller
{
    public function __construct(
        private CarService           $carService,
        private SearchHistoryService $searchHistoryService,
        private OrderService         $orderService)
    {
    }

    public function index(CarRequest $request)
    {
        $params           = $request->all();
        $params['status'] = CAR_PUBLIC;
        $metaSeo          = [
            'meta_title' => '管理車両一覧'
        ];
        $viewData         = [
            'meta_seo' => MetaSeoRender::setMetaSeo($metaSeo),
            'cars'     => $this->carService->getListSearch($params, auth('user')->id())->appends($request->query()),
        ];
        if (auth('user')->check()) {
            $this->searchHistoryService->store($request->except(['sort']), auth('user')->user());
        }
        return view('pages.car.list.index')->with($viewData);
    }

    public function show($id)
    {
        $car         = $this->carService->showWithFavorite($id, auth('user')->id());
        if(!$car){
            abort(404);
        }
        $isRequested = $this->orderService->checkIsExisted($id);
        $metaSeo     = [
            'meta_title' => $car->name
        ];
        $viewData    = [
            'meta_seo'    => MetaSeoRender::setMetaSeo($metaSeo),
            'car'         => $car,
            'isRequested' => $isRequested,
            'car_sames'   => $this->carService->getListSameBodyType($car->id, $car->body_type_id)
        ];
        return view('pages.car.detail.index')->with($viewData);
    }
}
