<?php

namespace App\Http\Controllers\User;

use App\Helpers\MetaSeoRender;
use App\Http\Controllers\Controller;
use App\Http\Requests\CarFavoriteRequest;
use App\Services\Api\CarFavoriteService;
use App\Services\Api\CarService;
use App\Traits\HasResponse;
use Illuminate\Http\Request;

class CarFavoriteController extends Controller
{
    use HasResponse;

    public function __construct(
        private CarService         $carService,
        private CarFavoriteService $carFavoriteService)
    {
    }

    public function index(CarFavoriteRequest $request)
    {
        $params   = $request->query();
        $params   = array_merge($params, [
            'status'        => CAR_PUBLIC,
            'only_favorite' => true
        ]);
        $metaSeo  = [
            'meta_title' => 'お気に入り車両'
        ];
        $viewData = [
            'meta_seo' => MetaSeoRender::setMetaSeo($metaSeo),
            'cars'     => $this->carService->getListSearch($params, auth('user')->id())
        ];
        return view('pages.car_favorite.index')->with($viewData);
    }

    public function store(CarFavoriteRequest $request)
    {
        $params            = $request->validated();
        $params['user_id'] = auth('user')->id();
        $result            = $this->carFavoriteService->store($params);
        return $result ? $this->responseSuccess($result) : $this->responseError();
    }
}
