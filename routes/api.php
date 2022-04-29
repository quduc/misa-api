<?php

declare(strict_types=1);

use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\ProductManagerController;
use App\Http\Controllers\Api\User\Auth\AuthController;
use App\Http\Controllers\Api\User\Auth\RegisterController;
use App\Http\Controllers\Api\User\MasterDataController;
use App\Http\Controllers\User\AccountController;
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
    $router->post('/auth/register', [RegisterController::class, 'register'])->name('register'); //ok
    $router->post('/auth/reset-password', [AuthController::class, 'resetPassword'])->name('resetPassword');
    $router->post('/auth/sent-otp', [RegisterController::class, 'prepareRegister'])->name('prepareRegister'); //ok

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
            $router->post('/change-password', [AccountController::class, 'updatePassword'])->name('updatePassword'); //ok
            $router->post('/update', [AccountController::class, 'updateUserProfile'])->name('updateUserProfile'); //ok
        });

    $router->name('product.')
        ->prefix('product')
        ->group(function (Router $router) {
            $router->get('/', [ProductController::class, 'index'])->name('index'); //ok
            $router->get('/hot', [ProductController::class, 'hotProductList'])->name('hotProductList');
            $router->get('/{id}', [ProductController::class, 'show'])->name('show'); //ok
        });

    $router->name('product_manager.')
        ->prefix('product-manager')
        ->group(function (Router $router) {
            $router->get('/', [ProductManagerController::class, 'index'])->name('index'); // ok
            $router->post('/', [ProductManagerController::class, 'store'])->name('store'); //ok
            $router->post('/{id}', [ProductManagerController::class, 'update'])->name('update'); //ok
            $router->get('/{id}', [ProductManagerController::class, 'show'])->name('show'); //ok
            $router->post('/{id}/delete', [ProductManagerController::class, 'delete'])->name('delete'); //ok
        });


    // $router->name('notification.')
    //     ->prefix('notification')
    //     ->group(function (Router $router) {
    //         $router->get('/', [NotificationController::class, 'index'])->name('index');
    //         $router->get('/{id}', [NotificationController::class, 'show'])->name('show');
    //     });
});
