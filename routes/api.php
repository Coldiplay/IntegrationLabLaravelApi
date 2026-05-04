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
            ->name('chat.index');
        Route::post('/', [ChatController::class, 'store'])
            ->name('chat.store');

        Route::group(['prefix' => '{chat}'], function () {
            Route::get('/', [ChatController::class, 'show'])
                ->name('api.chat.show');
            Route::put('/', [ChatController::class, 'update'])
                ->name('api.chat.update');
            Route::delete('/', [ChatController::class, 'destroy'])
                ->name('api.chat.destroy');

            Route::get('/members', [ChatController::class, 'getMembers'])
                ->name('api.chat.members');
            Route::post('/addmember', [ChatController::class, 'addMember'])
                ->name('api.chat.addmember');
            Route::post('/removemember', [ChatController::class, 'removeMember'])
                ->name('api.chat.removemember');

            Route::get('/messages', [ChatController::class, 'getMessages'])
                ->name('api.chat.messages');
            Route::post('/sendMessage', [ChatController::class, 'sendMessage'])
                ->name('message.send');
            Route::put('/updateMessage', [ChatController::class, 'updateMessage'])
                ->name('message.update');
            Route::delete('/deleteMessage', [ChatController::class, 'deleteMessage'])
                ->name('message.delete');
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

        Route::Post('/{shipping}/start', [ShippingController::class, 'confirmStart']);
        Route::Post('/{shipping}/ent', [ShippingController::class, 'confirmEnd']);
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
