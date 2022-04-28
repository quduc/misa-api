<?php

namespace App\Http\Controllers\User;

use App\Helpers\MetaSeoRender;
use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\Account\UpdatePasswordRequest;
use App\Services\Api\UserService;
use App\Traits\HasResponse;
use Illuminate\Http\Request;

class AccountController extends ApiController
{
    use HasResponse;

    public function __construct(private UserService $userService)
    {
    }

    public function updatePassword(UpdatePasswordRequest $request)
    {
        $result  = $this->userService->updatePassword($request->validate());
        return $result ? $this->responseSuccess() : $this->responseError();
    }
    public function updateUserProfile(Request $request)
    {
        $result = $this->userService->update(auth('api')->id(), $request->all());
        return $this->json(
            $result ?: [],
            $result ? 'success' : 'error',
            $result ? 200 : 400
        );
    }
}
