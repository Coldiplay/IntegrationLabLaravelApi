<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\ChatController;
use App\Http\Controllers\API\DriverController;
use App\Http\Controllers\API\IncidentController;
use App\Http\Controllers\API\ShippingController;
use App\Http\Controllers\API\ShippingOrderController;
use App\Http\Controllers\API\VehicleController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Swagger Documentation - редирект на Swagger UI
Route::get('/docs', function () {
    return redirect()->to('/spectrum/openapi.html');
});
Route::post('/register', [AuthController::class, 'register'])->name('api.register');
Route::post('/login', [AuthController::class, 'login'])->name('api.login');

// Protected routes (требуют авторизации)
Route::middleware('auth:sanctum')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout'])->name('api.logout');
    Route::get('/user', [AuthController::class, 'user'])->name('api.user');

    Route::group(['prefix' => 'chat'], function () {
        Route::get('/', [ChatController::class, 'index'])
            ->name('api.chat.index');
        Route::post('/', [ChatController::class, 'store'])
            ->name('api.chat.store');

        Route::group(['prefix' => '{chat}'], function () {
            Route::get('/', [ChatController::class, 'show'])
                ->name('api.chat.show');
            Route::put('/', [ChatController::class, 'update'])
                ->name('api.chat.update');
            Route::delete('/', [ChatController::class, 'destroy'])
                ->name('api.chat.destroy');

            Route::get('/members', [ChatController::class, 'getMembers'])
                ->name('api.chat.members.index');
            Route::post('/addMember', [ChatController::class, 'addMember'])
                ->name('api.chat.members.store');
            Route::delete('/removeMember/{user}', [ChatController::class, 'removeMember'])
                ->name('api.chat.members.destroy');

            Route::get('/messages', [ChatController::class, 'getMessages'])
                ->name('api.chat.messages.index');
            Route::post('/sendMessage', [ChatController::class, 'sendMessage'])
                ->name('api.chat.message.store');
            Route::put('/updateMessage', [ChatController::class, 'updateMessage'])
                ->name('api.chat.message.update');
            Route::delete('/deleteMessage', [ChatController::class, 'deleteMessage'])
                ->name('api.chat.message.destroy');
        });
        /*
        Route::get('/{chat}', [ChatController::class, 'show'])
            ->name('chat.show');
        Route::put('/{chat}', [ChatController::class, 'update'])
            ->name('chat.update');
        Route::delete('/{chat}', [ChatController::class, 'destroy'])
            ->name('chat.destroy');
        */
        /*
        Route::post('/{chat}/addMember', [ChatController::class, 'addMember'])
            ->name('chat.add-member');
        Route::post('/{chat}/removeMember', [ChatController::class, 'removeMember'])
            ->name('chat.remove-member');
        */
    });

    Route::group(['prefix' => 'vehicle'], function () {
        //CRUD
        Route::get('/', [VehicleController::class, 'index'])
            ->name('api.vehicle.index');
        Route::post('/', [VehicleController::class, 'store'])
            ->name('api.vehicle.store');
        Route::get('/{vehicle}', [VehicleController::class, 'show'])
            ->name('api.vehicle.show');
        Route::put('/{vehicle}', [VehicleController::class, 'update'])
            ->name('api.vehicle.update');
        Route::delete('/{vehicle}', [VehicleController::class, 'destroy'])
            ->name('api.vehicle.destroy');
        //
    });

    Route::group(['prefix' => 'incident'], function () {
        //CRUD
        Route::get('/', [IncidentController::class, 'index'])
            ->name('api.incident.index');
        Route::post('/', [IncidentController::class, 'store'])
            ->name('api.incident.store');
        Route::get('/{incident}', [IncidentController::class, 'show'])
            ->name('api.incident.show');
        Route::put('/{incident}', [IncidentController::class, 'update'])
            ->name('api.incident.update');
        Route::delete('/{incident}', [IncidentController::class, 'destroy'])
            ->name('api.incident.destroy');
        //
    });

    Route::group(['prefix' => 'shipping'], function () {
        //CRUD
        Route::get('/', [ShippingController::class, 'index'])
            ->name('api.shipping.index');
        Route::post('/', [ShippingController::class, 'store'])
            ->name('api.shipping.store');
        Route::get('/{shipping}', [ShippingController::class, 'show'])
            ->name('api.shipping.show');
        Route::put('/{shipping}', [ShippingController::class, 'update'])
            ->name('api.shipping.update');
        Route::delete('/{shipping}', [ShippingController::class, 'destroy'])
            ->name('api.shipping.destroy');

        Route::patch('/{shipping}/start', [ShippingController::class, 'confirmStart'])
            ->name('api.shipping.start');
        Route::patch('/{shipping}/end', [ShippingController::class, 'confirmEnd'])
            ->name('api.shipping.end');
        //
    });

    Route::group(['prefix' => 'shipping-order'], function () {
        //CRUD
        Route::get('/', [ShippingOrderController::class, 'index'])
            ->name('api.shipping-order.index');
        Route::post('/', [ShippingOrderController::class, 'store'])
            ->name('api.shipping-order.store');
        Route::get('/{shippingOrder}', [ShippingOrderController::class, 'show'])
            ->name('api.shipping-order.show');
        Route::put('/{shippingOrder}', [ShippingOrderController::class, 'update'])
            ->name('api.shipping-order.update');
        Route::delete('/{shippingOrder}', [ShippingOrderController::class, 'destroy'])
            ->name('api.shipping-order.destroy');
    });

    Route::group(['prefix' => 'driver'], function () {
        //CRUD
        Route::get('/', [DriverController::class, 'index'])
            ->name('api.driver.index');
        Route::post('/', [DriverController::class, 'store'])
            ->name('api.driver.store');
        Route::get('/{driver}', [DriverController::class, 'show'])
            ->name('api.driver.show');
        Route::put('/{driver}', [DriverController::class, 'update'])
            ->name('api.driver.update');
        Route::delete('/{driver}', [DriverController::class, 'destroy'])
            ->name('api.driver.destroy');
        //
    });
});




