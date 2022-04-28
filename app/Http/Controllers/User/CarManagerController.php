<?php

namespace App\Http\Controllers\User;

use App\Helpers\MetaSeoRender;
use App\Http\Controllers\Controller;
use App\Http\Requests\CarManagerRequest;
use App\Http\Requests\User\UpdateCarStatusRequest;
use App\Mail\ToAdmin\NewCarRegister;
use App\Models\Admin;
use App\Services\Api\CarService;
use App\Services\Api\ProvinceService;
use App\Traits\HasResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class CarManagerController extends Controller
{
    use HasResponse;

    public function __construct(
        private CarService      $carService,
        private ProvinceService $provinceService)
    {
    }

    public function index(Request $request)
    {
        $params            = $request->all();
        $params['user_id'] = auth()->id();

        $viewData = [
            'meta_seo'  => MetaSeoRender::setMetaSeo(['meta_title' => '掲載車両一覧']),
            'cars' => $this->carService->getListSearch($params)
        ];

        return view('pages.car_manager.list.index')->with($viewData);
    }

    public function show($id)
    {
        $car = $this->carService->show($id, auth()->id());
        if (!$car) abort(404);

        $viewData = [
            'meta_seo'  => MetaSeoRender::setMetaSeo(['meta_title' => $car->name]),
            'car' => $car
        ];
        return view('pages.car_manager.detail.index')->with($viewData);
    }

    public function create()
    {
        $viewData = [
            'meta_seo'  => MetaSeoRender::setMetaSeo(['meta_title' => '管理車両編集']),
            'provinces' => $this->provinceService->getList(),
        ];
        return view('pages.car_manager.form')->with($viewData);
    }

    public function edit($id)
    {
        $car = $this->carService->show($id, auth()->id());
        if (!$car) abort(404);
        $viewData = [
            'meta_seo'  => MetaSeoRender::setMetaSeo(['meta_title' => '管理車両追加']),
            'car'       => $car,
            'provinces' => $this->provinceService->getList(),
        ];
        return view('pages.car_manager.form')->with($viewData);
    }

    public function delete($id)
    {
        $result = $this->carService->delete($id);
        return $result ? $this->responseSuccess() : $this->responseError();
    }

    public function release($id)
    {
        $result = $this->carService->release($id);
        return $result ? $this->responseSuccess() : $this->responseError();
    }

    public function store(CarManagerRequest $request)
    {
        $params            = $request->all();
        $params['user_id'] = auth()->id();
        $result            = $this->carService->store($params);
        return $result ? $this->responseData($result) : $this->responseError();
    }

    public function update(CarManagerRequest $request, $id)
    {
        $result = $this->carService->update($id, $request->all());
        return $result ? $this->responseSuccess() : $this->responseError();
    }

    public function requestPublic($id)
    {
        $result = $this->carService->requestPublic($id);
        return $result ? $this->responseSuccess('車両掲載申請を受け付けました。<br> 事務局からの審査結果のご連絡まで、お待ちください。') : $this->responseError();
    }

    public function updateStatus($id, UpdateCarStatusRequest $request)
    {
        $status = $request->get('status');
        $result = $this->carService->updateStatus($id, $status);
        $mgs = $status == CAR_PUBLIC ? '車両情報を公開しました。' : '車両情報を非公開にしました。';
        return $result ? $this->responseSuccess($mgs) : $this->responseError();
    }
}
