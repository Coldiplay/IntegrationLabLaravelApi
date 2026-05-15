<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\ChatController;
use App\Http\Controllers\API\DriverController;
use App\Http\Controllers\API\DriversShiftController;
use App\Http\Controllers\API\IncidentController;
use App\Http\Controllers\API\MessageController;
use App\Http\Controllers\API\ShippingController;
use App\Http\Controllers\API\ShippingOrderController;
use App\Http\Controllers\API\VehicleController;
use App\Http\Controllers\Webhook\SmsStatusWebhookController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::post('/webhooks/sms/status', [SmsStatusWebhookController::class, 'handle'])
    ->name('webhooks.sms.status');

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


            Route::group(['prefix' => 'messages'], function () {
                Route::get('/', [ChatController::class, 'getMessages'])
                    ->name('api.chat.messages.index');
                Route::post('/', [ChatController::class, 'sendMessage'])
                    ->name('api.chat.message.store');
            });
        });
    });
    Route::group(['prefix' => 'message/{message}'], function () {
       Route::get('/', [MessageController::class, 'show'])
       ->name('api.message.show');
       Route::put('/', [MessageController::class, 'update'])
       ->name('api.message.update');
       Route::delete('/', [MessageController::class, 'destroy'])
       ->name('api.message.destroy');
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

        Route::group(['prefix' => '{shipping}'], function () {
           Route::get('/', [ShippingController::class, 'show'])
               ->name('api.shipping.show');
           Route::put('/', [ShippingController::class, 'update'])
               ->name('api.shipping.update');
           Route::delete('/', [ShippingController::class, 'destroy'])
               ->name('api.shipping.destroy');
        });



    });
    Route::group(['prefix' => 'shift'], function () {
        Route::get('/', [DriversShiftController::class, 'index'])
            ->name('api.shift.index');
        Route::put('/{shipping}/start', [DriversShiftController::class, 'store'])
            ->name('api.shift.start');

        Route::group(['prefix' => '{shift}'], function () {
           Route::get('/', [DriversShiftController::class, 'show'])
               ->name('api.shift.show');

           Route::patch('/', [DriversShiftController::class, 'update'])
               ->name('api.shift.end');

           Route::delete('/', [DriversShiftController::class, 'destroy'])
               ->name('api.shift.destroy');
        });
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




