<?php

declare(strict_types=1);

use App\Http\Controllers\Admin\Auth\ForgotPasswordController;
use App\Http\Controllers\Api\User\Auth\AuthController;
use App\Http\Controllers\Api\User\Auth\RegisterController;
use App\Http\Controllers\Api\User\MasterDataController;
use App\Http\Controllers\User\AccountController;
use App\Http\Controllers\User\NotificationController;
use Illuminate\Routing\Router;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/



// guest
Route::group(['middleware' => ['guest']], function (Router $router) {
    $router->post('/auth/prepare-login', [AuthController::class, 'checkPhoneExists'])->name('checkPhoneExists'); //ok
    $router->post('/auth/login', [AuthController::class, 'login'])->name('login'); //ok
    $router->post('/auth/forget-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('forget_password');
    $router->post('/auth/register', [RegisterController::class, 'register'])->name('register');
    $router->post('/auth/prepare-register', [RegisterController::class, 'prepareRegister'])->name('prepareRegister');

    $router->name('master_data.')
        ->prefix('master-data')
        ->group(function (Router $router) {
            $router->get('/categories', [MasterDataController::class, 'categories'])->name('categories'); //ok
        });
});

// auth
Route::group(['middleware' => ['auth:api']], function (Router $router) {
    $router->name('me.')
        ->prefix('me')
        ->group(function (Router $router) {
            $router->get('/', [AuthController::class, 'me'])->name('me'); //ok
            $router->post('/change-password', [AccountController::class, 'updatePassword'])->name('update_password');
            $router->post('/update', [AccountController::class, 'updateUserProfile'])->name('updateUserProfile');
        });

    // $router->name('notification.')
    //     ->prefix('notification')
    //     ->group(function (Router $router) {
    //         $router->get('/', [NotificationController::class, 'index'])->name('index');
    //         $router->get('/{id}', [NotificationController::class, 'show'])->name('show');
    //     });
});
