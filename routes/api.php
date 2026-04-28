<?php

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


// Protected routes (требуют авторизации)
Route::middleware('auth:sanctum')->group(function () {

    Route::group(['prefix' => 'chat'], function () {
        //CRUD
        Route::get('/', [ChatController::class, 'index'])
            ->name('chat.index');
        Route::post('/', [ChatController::class, 'store'])
            ->name('chat.store');
        Route::get('/{chat}', [ChatController::class, 'show'])
            ->name('chat.show');
        Route::put('/{chat}', [ChatController::class, 'update'])
            ->name('chat.update');
        Route::delete('/{chat}', [ChatController::class, 'destroy'])
            ->name('chat.destroy');
        //

        Route::post('/sendMessage', [ChatController::class, 'sendMessage'])
            ->name('message.send');
        Route::put('/updateMessage', [ChatController::class, 'updateMessage'])
            ->name('message.update');
        Route::delete('/deleteMessage', [ChatController::class, 'deleteMessage'])
            ->name('message.delete');

        Route::post('/{chat}/addMember', [ChatController::class, 'addMember'])
            ->name('chat.add-member');
        Route::post('/{chat}/removeMember', [ChatController::class, 'removeMember'])
            ->name('chat.remove-member');
    });

    Route::group(['prefix' => 'vehicle'], function () {
        //CRUD
        Route::get('/', [VehicleController::class, 'index'])
            ->name('vehicle.index');
        Route::post('/', [VehicleController::class, 'store'])
            ->name('vehicle.store');
        Route::get('/{vehicle}', [VehicleController::class, 'show'])
            ->name('vehicle.show');
        Route::put('/{vehicle}', [VehicleController::class, 'update'])
            ->name('vehicle.update');
        Route::delete('/{vehicle}', [VehicleController::class, 'destroy'])
            ->name('vehicle.destroy');
        //
    });

    Route::group(['prefix' => 'incident'], function () {
        //CRUD
        Route::get('/', [IncidentController::class, 'index'])
            ->name('incident.index');
        Route::post('/', [IncidentController::class, 'store'])
            ->name('incident.store');
        Route::get('/{incident}', [IncidentController::class, 'show'])
            ->name('incident.show');
        Route::put('/{incident}', [IncidentController::class, 'update'])
            ->name('incident.update');
        Route::delete('/{incident}', [IncidentController::class, 'destroy'])
            ->name('incident.destroy');
        //
    });

    Route::group(['prefix' => 'shipping'], function () {
        //CRUD
        Route::get('/', [ShippingController::class, 'index'])
            ->name('shipping.index');
        Route::post('/', [ShippingController::class, 'store'])
            ->name('shipping.store');
        Route::get('/{shipping}', [ShippingController::class, 'show'])
            ->name('shipping.show');
        Route::put('/{shipping}', [ShippingController::class, 'update'])
            ->name('shipping.update');
        Route::delete('/{shipping}', [ShippingController::class, 'destroy'])
            ->name('shipping.destroy');
        //
    });

    Route::group(['prefix' => 'shipping-order'], function () {
        //CRUD
        Route::get('/', [ShippingOrderController::class, 'index'])
            ->name('shipping-order.index');
        Route::post('/', [ShippingOrderController::class, 'store'])
            ->name('shipping-order.store');
        Route::get('/{shippingOrder}', [ShippingOrderController::class, 'show'])
            ->name('shipping-order.show');
        Route::put('/{shippingOrder}', [ShippingOrderController::class, 'update'])
            ->name('shipping-order.update');
        Route::delete('/{shippingOrder}', [ShippingOrderController::class, 'destroy'])
            ->name('shipping-order.destroy');
    });

    Route::group(['prefix' => 'driver'], function () {
        //CRUD
        Route::get('/', [DriverController::class, 'index'])
            ->name('driver.index');
        Route::post('/', [DriverController::class, 'store'])
            ->name('driver.store');
        Route::get('/{driver}', [DriverController::class, 'show'])
            ->name('driver.show');
        Route::put('/{driver}', [DriverController::class, 'update'])
            ->name('driver.update');
        Route::delete('/{driver}', [DriverController::class, 'destroy'])
            ->name('driver.destroy');
        //
    });
});
